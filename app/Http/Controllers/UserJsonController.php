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
        $maxId = 0;
        if (!empty($users)) {
            $maxId = max(array_column($users, 'id'));
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }
        $newUser = [
            'id' => $maxId + 1,
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

    public function getUserDetail($id)
    {
        $users = $this->readJson();
        $user = collect($users)->firstWhere('id', (int)$id);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Error in get User']);
        }
        return response()->json(['status' => 'success', 'user' => $user]);
    }


    public function deleteUser($id)
    {
        $users = $this->readJson();
        $users = collect($users)->reject(function ($u) use ($id) {
            return $u['id'] == (int) $id;
        })->values()->all();
        $this->writeJson($users);
        return response()->json(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

    public function editUser($id, Request $request)
    {
        $users = $this->readJson();
        $index = collect($users)->search(function ($u) use ($id) {
            return $u['id'] == (int) $id;
        });

        if ($index === false) {
            return response()->json(['status' => 'error', 'message' => 'User Not Found']);
        }
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $users[$index]['image'] = $imagePath;
        }
        $users[$index]['name'] = $request->name;
        $users[$index]['email'] = $request->email;
        $users[$index]['mobile'] = $request->mobile;
        $users[$index]['description'] = $request->description;
        $this->writeJson($users);
        return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
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