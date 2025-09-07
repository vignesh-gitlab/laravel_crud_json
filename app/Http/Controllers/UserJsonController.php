<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserJsonController extends Controller
{
    private $file = 'sample.json';
    public function getUsers()
    {
        $users = $this->readJson();

        $html = '';

        if (count($users) > 0) {
            $i = 1;
            foreach ($users as $user) {
                $html .= '<tr>';
                $html .= '<td>' . $i++ . '</td>';
                $html .= '<td>' . e($user['name']) . '</td>';
                $html .= '<td>' . e($user['email']) . '</td>';
                $html .= '<td>' . e($user['mobile']) . '</td>';
                $html .= '<td>';
                if (!empty($user['image'])) {
                    $html .= '<img src="' . asset('storage/' . $user['image']) . '" width="60">';
                } else {
                    $html .= 'No Image';
                }
                $html .= '</td>';
                $html .= '<td>' . e($user['description']) . '</td>';
                $html .= '<td>
                        <button class="btn btn-warning edit" data-id="' . $user['id'] . '">Edit</button>
                        <button class="btn btn-danger delete" data-id="' . $user['id'] . '">Delete</button>
                      </td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="7" class="text-center">No Data Found</td></tr>';
        }

        return response()->json(['html' => $html]);
    }


    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:240',
            'email' => 'required|email',
            'mobile' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'description' => 'required'
        ]);
        $users = $this->readJson();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }
        $newUser = [
            'id' => count($users) + 1,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'image' => $imagePath,
            'description' => $validated['description'],
        ];
        $users[] = $newUser;
        $this->writeJson($users);
        return response()->json(['status' => 'success', 'message' => "User Created"]);
    }

    private function readJson()
    {
        if (!Storage::disk('local')->exists($this->file)) {
            return [];
        }
        return json_decode(Storage::disk('local')->get($this->file), true) ?? [];
    }

    private function writeJson($data)
    {
        Storage::disk('local')->put($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
}