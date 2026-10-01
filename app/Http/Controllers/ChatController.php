<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat'); // Hiển thị giao diện chat.blade.php
    }

    public function sendMessage(Request $request)
    {
    $response = Http::timeout(180)->post('http://127.0.0.1:5000/chat', [
        'message' => $request->message
    ]);

    // Log dữ liệu nhận từ Flask
    \Log::info('📩 Request gửi đến Flask:', ['message' => $request->input('message')]);
    \Log::info('📤 Phản hồi từ Flask:', ['data' => $response->json()]);

    return response()->json($response->json());
}
}
