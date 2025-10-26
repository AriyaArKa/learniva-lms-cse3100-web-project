<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;
use Intervention\Image\Laravel\Facades\Image;

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

    public function SiteSetting()
    {
        $site = SiteSetting::first();

        // If no site settings exist, create a default one
        if (!$site) {
            $site = SiteSetting::create([
                'phone' => '',
                'email' => '',
                'address' => '',
                'facebook' => '',
                'twitter' => '',
                'copyright' => '',
                'logo' => '',
            ]);
        }

        return view('admin.backend.setting.site_setting', compact('site'));

    }// End Method 

    public function UpdateSite(Request $request)
    {

        $site_id = $request->id;

        if ($request->file('logo')) {

            $image = $request->file('logo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Ensure the upload/logo directory exists
            $upload_path = public_path('upload/logo/');
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            Image::read($image)->resize(140, 41)->save($upload_path . $name_gen);
            $save_url = 'upload/logo/' . $name_gen;

            SiteSetting::find($site_id)->update([
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'copyright' => $request->copyright,
                'logo' => $save_url,

            ]);

            $notification = array(
                'message' => 'Site Setting Updated with image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);

        } else {

            SiteSetting::find($site_id)->update([
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'copyright' => $request->copyright,

            ]);

            $notification = array(
                'message' => 'Site Setting Updated without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);

        } // end else 

    }// End Method 

}
