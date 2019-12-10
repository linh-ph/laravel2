<?php

namespace App\Http\Controllers;

use App\LinhVuc;
use App\NguoiChoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ThemNguoiChoiRequest;
use App\Http\Requests\CapNhatNguoiChoiRequest;
use Illuminate\Support\Facades\Session;
use Mail;
use Symfony\Component\Mime\Message;

class NguoiChoiController extends Controller
{
    public function index()
    {
        //
        $nguoiChois = NguoiChoi::all();
        return view('nguoi-choi.danh-sach', compact('nguoiChois'));
    }

    public function create()
    {
        // hien thi form them nguoi choi
        return view('nguoi-choi.them-moi');
    }

    public function store(ThemNguoiChoiRequest $request)
    {
        // luu nguoi choi moi vao database
        $nguoiChoi                = new NguoiChoi();
        $nguoiChoi->ten_dang_nhap = $request->ten_dang_nhap;
        $nguoiChoi->mat_khau      = Hash::make($request->mat_khau);
        $nguoiChoi->email         = $request->email;
        $nguoiChoi->hinh_dai_dien = $request->hinh_dai_dien;
        $nguoiChoi->diem_cao_nhat = $request->diem_cao_nhat;
        $nguoiChoi->credit        = $request->credit;
        $nguoiChoi->save();
        $msg = "Thêm người chơi thành công";
        return view('nguoi-choi.them-moi', compact('msg'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // hiển thị form cập nhật theo id 
        $nguoiChoi = NguoiChoi::find($id);
        return view('nguoi-choi.them-moi', compact('nguoiChoi'));
    }

    public function update(CapNhatNguoiChoiRequest $request, $id)
    {
        // cập nhật người chơi vào database
        $nguoiChoi = NguoiChoi::find($id);
        $nguoiChoi->ten_dang_nhap = $request->ten_dang_nhap;
        $nguoiChoi->mat_khau      = Hash::make($request->mat_khau);
        $nguoiChoi->email         = $request->email;
        $nguoiChoi->hinh_dai_dien = $request->hinh_dai_dien;
        $nguoiChoi->diem_cao_nhat = $request->diem_cao_nhat;
        $nguoiChoi->credit        = $request->credit;
        $nguoiChoi->save();
        $msg = "Cập nhật người chơi thành công";
        return view('nguoi-choi.them-moi', compact('msg'));
    }

    public function destroy($id)
    {
        // cập nhật trạng thái deleted vào database, ẩn item trên danh sách
        $nguoiChoi = NguoiChoi::find($id);
        $nguoiChoi->delete();
        return redirect()->route('nguoi-choi.danh-sach');
    }
    //
    public function send()
    {
        return view('gui-mail.form-gui-mail');
        // Mail::send(['text' => 'mail'], ['adsasdf', 'ngoclecaothang@gmail.com'], function ($message) {
        //     //$message->to('ngoclecaothang@gmail.com', 'ngocle')->subject('Test Email');
        //     $message->from('ngoclecaothang@gmail.com', 'ngocle')->subject('Ma no loi qoai!');;
        // });
    }

    public function sendMail(Request $request)
    {
        $data = array('email' => $request->send_email);
        //dd($data);
        Mail::send('gui-mail.content_mail_send', $data, function($message) use ($data) {
            $message->to($data['email'], 'Admin')->subject('Lấy lại mật khẩu từ Admin Lễ Ngọc');
        });
        return back()->with('success', 'Gửi yêu cầu thành công');
    }
}
