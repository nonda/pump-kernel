<?php
namespace Nonda\Util;

use function GuzzleHttp\Psr7\mimetype_from_filename;
use Illuminate\Support\Facades\Mail;

class MailUtil
{
    const ADMIN_EMAIL_LIST = [
        'jinglei+119@nonda.us',
        'sensen+119@nonda.us',
        'rivsen@outlook.com',
    ];

    public static function mailToAdmin($title, $content,
                                       $to = MailUtil::ADMIN_EMAIL_LIST
    ){
        if (!self::isMailable()) {
            return;
        }

        $to = (array) $to;

        foreach ($to as $email) {
            $data['email'] = $email;
            $data['title'] = $title;
            Mail::raw($content, function ($message) use ($data) {
                $message->from('119@nonda.us', 'nonda119救火中心');
                $message->to($data['email'])->subject($data['title']);
            });
        }

    }

    public static function mailToAdminWithAttach($title, $content, $filePath, $display = null, $mime =null,
                                       $to = MailUtil::ADMIN_EMAIL_LIST
    ){
        if (!self::isMailable()) {
            return;
        }

        $to = (array) $to;

        $data['title'] = $title;
        $data['filePath'] = $filePath;
        $data['display'] = $display ?: basename($filePath);

        if (!$mime) {
            $mime = mimetype_from_filename($data['display']);

            if (!$mime) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->buffer(file_get_contents($filePath));
            }
        }

        $data['mime'] = $mime;

        foreach ($to as $email) {
            $data['email'] = $email;

            Mail::raw($content, function ($message) use ($data) {
                $message->from('119@nonda.us', 'nonda119救火中心');
                $message->to($data['email'])->subject($data['title']);
                $message->attach($data['filePath'] ,['as' => $data['display'], 'mime' => $data['mime']]);
            });
        }

    }

    private static function isMailable()
    {
        if (config('app.env') == 'test') {
            return false;
        }

        return true;
    }
}
