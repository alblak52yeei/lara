<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function showForm()
    {
        return view('form');
    }

    public function submitForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'created_at' => now()->toDateTimeString(),
        ];

        $filename = 'form_data_' . uniqid() . '.json';
        Storage::put($filename, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return redirect()->route('form.show')
            ->with('success', 'Данные успешно сохранены!');
    }

    public function showData()
    {
        $files = Storage::files();
        $data = [];

        foreach ($files as $file) {
            if (str_contains($file, 'form_data_')) {
                $content = Storage::get($file);
                $jsonData = json_decode($content, true);
                if ($jsonData) {
                    $jsonData['filename'] = $file;
                    $data[] = $jsonData;
                }
            }
        }

        // Сортируем по дате создания (новые сверху)
        usort($data, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return view('data', compact('data'));
    }
}
