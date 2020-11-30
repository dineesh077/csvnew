<?php
namespace App\Http\Controllers;

use App\csv;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class CsvController extends Controller
{

    public function index()
    {
        return view('csv_form');
    }

    /*function for upload csv file*/
    public function uploadFile(Request $request)
    {
        
        if ($request->input('submit') != null)
        {
            
            $file = $request->file('csv_name');

            // File Details
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            // Valid File Extensions
            $valid_extension = array(
                "csv"
            );

            // Check file extension
            if (in_array(strtolower($extension) , $valid_extension))
            {

                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location, $filename);

                // Import CSV to Database
                $filepath = public_path($location . "/" . $filename);

                // Reading file
                $file = fopen($filepath, "r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ",")) !== false)
                {
                    $num = count($filedata);

                    // Skip first row (Remove below comment if you want to skip the first row)
                    /*if($i == 0){
                    $i++;
                    continue;
                    }*/
                    for ($c = 0;$c < $num;$c++)
                    {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }
                fclose($file);

                //echo "<pre>"; print_r($importData_arr); die;
                // Insert to MySQL database
                $errors = [];
                foreach ($importData_arr as $i => $importData)
                {
                    if ($i != 0)
                    {
                        if (!preg_match('/^[a-zA-Z0-9_.-]*$/', $importData[0]))
                        {
                            $errors[] = "Module Code contains symbols at row  " . $i;
                        }
                        if (empty($importData[1]))
                        {
                            $errors[] = "Module name is missing at row  " . $i;
                        }

                        if (empty($importData[2]))
                        {
                            $errors[] = "Term name is missing at row  " . $i;
                        }

                        if (!preg_match('/^[a-zA-Z0-9]*$/', $importData[2]))
                        {
                            $errors[] = "Term Name contains symbols at row  " . $i;
                        }
                    }
                    else
                    {
                        if (trim($importData[0]) != 'Module_code')
                        {
                            $errors = "Header column (Module_Code at 1st column) is incorrect in csv file";
                        }

                        if (trim($importData[1]) != 'Module_name')
                        {
                            $errors = "Header column (Module_name at 2nd column) is incorrect in csv file";
                        }

                        if (trim($importData[2]) != 'Module_term')
                        {
                            $errors = "Header column (Module_term at 3rd column) is incorrect in csv file";
                        }
                    }
                    if ($i != 0)
                    {
                        $csv = new csv;
                        $csv->csv_module_code = $importData[0];
                        $csv->csv_module_name = $importData[1];
                        $csv->csv_module_term = $importData[2];
                        $csv->save();
                    }

                }
                if (!empty($errors))
                {
                    
                    $mail = new PHPMailer();
                    $mail->CharSet = 'UTF-8';
                    $mail->Encoding = 'base64';

                    $mail->IsSMTP();
                    //$mail->SMTPDebug = 1;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'tls';
                    $mail->Host = "smtp.gmail.com";
                    $mail->Port = 587;
                    $mail->IsHTML(true);
                    $mail->Username = "thowmaildelivery@gmail.com";
                    $mail->Password = "Tutor123How";
                    $mail->SetFrom("thowmaildelivery@gmail.com", "Dineesh");

                    $mail->addReplyTo("thowmaildelivery@gmail.com");
                    $mail->AddAddress("charush@accubits.com"); 
                    $mail->Subject = "CSV Upload Errors";
                    $mail->Body = "<html>
                                <body>
                                    <p>
                                        <b>Time sent:</b> " . date('d-m-Y h:i A') . "<br/>
                                        <b>Message:</b> <br/>";
                                        foreach ($errors as $er)
                                        {
                                            $mail->Body .= $er . "<br/>";
                                        }
                    $mail->Body .= "</p>
                                   
                                </body>
                            </html>";
                    $test = $mail->Send();
                }

                return redirect('/')->with('message', 'Import Successful.');

            }
            else
            {
                return redirect('/')->with('error-message', 'Invalid File Extension!');
            }

        }

    }
}

