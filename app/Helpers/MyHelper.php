<?php
namespace App\Helpers;

use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MyHelper
{
    public static function get_uploaded_file_url($url)
    {
        if ($url != NULL || $url != '') {
            return url(Storage::url($url));
        } else {
            return asset('img/default.png');
        }
    }

    public static function get_lang($lang)
    {
        switch(Str::lower($lang)) {
            case 'id':
                return 'Indonesia';
                break;
            case 'en':
                return 'English';
                break;
            default:
                return 'Indonesia';
        }
    }

    public static function get_status_label($status = '')
    {
        switch(Str::lower($status)) {
            case 'pending':
                $status = '<span class="badge badge-warning">PENDING</span>';
                break;
            case 'batal':
                $status = '<span class="badge badge-danger">'. Str::upper(__('messages.label_cancel')) .'</span>';
                break;
            case 'berhasil':
                $status = '<span class="badge badge-success">'. Str::upper(__('messages.label_success')) .'</span>';
                break;
            case 'gagal':
                $status = '<span class="badge badge-danger">'. Str::upper(__('messages.label_failed')) .'</span>';
                break;
            default:
                $status = '<span class="badge badge-info">Tidak Dikenal: '. $status .'</span>';
        }

        return $status;
    }

    public static function get_state_pengiriman($state = '')
    {
        switch(Str::lower($state)) {
            case 'dikirim':
                $state = '<span class="badge badge-warning">DIKIRIM</span>';
                break;
            case 'diterima':
                $state = '<span class="badge badge-success">DITERIMA</span>';
                break;
            default:
                $state = '<span class="badge badge-info">Menunggu Verifikasi</span>';
        }

        return $state;
    }

    public static function get_visibility_status($status)
    {
        if ($status == 1) {
            return '<div class="badge badge-success">Visible</div>';
        } else {
            return '<div class="badge badge-danger">Not Visible</div>';
        }
    }

    public static function get_selected_price($product)
    {
        $product = json_decode($product, true);
        $price = $product['is_discount'] == 1 ? $product['price_striked'] : $product['price'];
        return $price;
    }

    public static function get_lampiran($file, $title = 'Download')
    {
        $theFile = explode('.', $file);
        $ext = end($theFile);
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'bmp':
                $file = '
                    <div class="my-uploaded-file d-flex justify-content-start">
                        <div class="icon p-2 border d-flex align-items-center">
                            <div class="myLightbox mb-2">
                                <a href="' . url(Storage::url($file)) . '">
                                    <img src="' . url(Storage::url($file)) . '" alt="photo" width="19" height="24">
                                </a>
                            </div>
                        </div>
                        <div class="desc p-2 border myLightbox" style="font-size: 12px">
                            <a href="' . url(Storage::url($file)) . '" class="d-block">'. $title .'</a>
                            <b>('. MyHelper::get_size($file) .')</b>
                        </div>
                    </div>
                ';
                break;
            case 'pdf':
                $file = '<div class="my-uploaded-file d-flex justify-content-start">
                            <div class="icon p-2 border d-flex align-items-center">
                            <a href='. url(Storage::url($file)) .'>
                                <i class="fas fa-file-pdf" style="font-size: 24px;color:maroon"></i>
                            </a>
                            </div>
                            <div class="desc p-2 border" style="font-size: 12px">
                                <a href='. url(Storage::url($file)) .' class="d-block">'. $title .'</a>
                                <b>('. MyHelper::get_size($file) .')</b>
                            </div>
                        </div>';
                break;
            case 'doc':
            case 'docx':
                $file = '<div class="my-uploaded-file d-flex justify-content-start">
                        <div class="icon p-2 border d-flex align-items-center">
                        <a href='. url(Storage::url($file)) .'>
                            <i class="fas fa-file-word" style="font-size: 24px;color:dodgerblue"></i>
                        </a>
                        </div>
                        <div class="desc p-2 border" style="font-size: 12px">
                            <a href='. url(Storage::url($file)) .' class="d-block">'. $title .'</a>
                            <b>('. MyHelper::get_size($file) .')</b>
                        </div>
                    </div>';
                break;
            case 'xls':
            case 'xlsx':
                $file = '<div class="my-uploaded-file d-flex justify-content-start">
                            <div class="icon p-2 border d-flex align-items-center">
                            <a href='. url(Storage::url($file)) .'>
                                <i class="fas fa-file-excel" style="font-size: 24px;color:green"></i>
                            </a>
                            </div>
                            <div class="desc p-2 border" style="font-size: 12px">
                                <a href='. url(Storage::url($file)) .' class="d-block">'. $title .'</a>
                                <b>('. MyHelper::get_size($file) .')</b>
                            </div>
                        </div>';
                break;

            default:
                $file = '-';
                break;
        }
        return $file;
    }

    public static function get_size($file_path)
    {
        return number_format(Storage::size($file_path) / 1048576,2) . ' MB';
    }

    public static function get_grup_menu()
    {
        return [
            'template' => 'Template',
            'tool' => 'Tool',
        ];
    }

    public static function rupiah($angka)
    {
        return number_format($angka ?? 0, 0, ",",".");
    }

    public static function ipaymu_signature($body)
    {
        //Generate Signature
        // *Don't change this
        $va           = config('setting.ipaymu_va');
        $secret       = config('setting.ipaymu_api');

        $method       = 'POST'; //method
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        return $signature;
    }

    public static function check_only_for_member($user_id)
    {
        if (User::find($user_id)->role != 'MEMBER') {
            return ResponseFormatter::success(['is_success' => 'only_member'], 'Hanya boleh dilakukan oleh akun dengan role MEMBER');
            exit();
        }
    }
}



?>
