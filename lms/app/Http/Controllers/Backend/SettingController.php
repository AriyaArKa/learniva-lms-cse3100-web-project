<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{

    public function SmtpSetting()
    {

        $smpt = SmtpSetting::first();

        // If no SMTP settings exist, create a default one
        if (!$smpt) {
            $smpt = SmtpSetting::create([
                'mailer' => 'smtp',
                'host' => 'localhost',
                'port' => '587',
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
                'from_address' => 'noreply@example.com',
            ]);
        }

        return view('admin.backend.setting.smpt_update', compact('smpt'));

    }// End Method 

    public function SmtpUpdate(Request $request)
    {

        $smtp_id = $request->id;

        $smtpSetting = SmtpSetting::find($smtp_id);

        if ($smtpSetting) {
            $smtpSetting->update([
                'mailer' => $request->mailer,
                'host' => $request->host,
                'port' => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'encryption' => $request->encryption,
                'from_address' => $request->from_address,
            ]);
        } else {
            // If record doesn't exist, create a new one
            SmtpSetting::create([
                'mailer' => $request->mailer,
                'host' => $request->host,
                'port' => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'encryption' => $request->encryption,
                'from_address' => $request->from_address,
            ]);
        }

        $notification = array(
            'message' => 'Smtp Setting Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }// End Method 

}
