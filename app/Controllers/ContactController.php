<?php

namespace App\Controllers;

use App\Middlewares\AuthMiddleware;
use App\Models\Contact;
use Lune\Http\Controller;
use Lune\Http\Request;

class ContactController extends Controller {
    public function __construct() {
        $this->setMiddlewares([AuthMiddleware::class]);
    }

    public function index() {
        return view('contacts/index', ['contacts' => Contact::all()]);
    }

    public function create() {
        return view('contacts/create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        Contact::create([...$data, 'user_id' => auth()->id()]);

        return redirect('/contacts');
    }

    public function edit(Contact $contact) {
        return view('contacts/edit', ['contact' => $contact]);
    }

    public function update(Contact $contact, Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'phone_number' => 'required'
        ]);

        $contact->name = $data['name'];
        $contact->phone_number = $data['phone_number'];
        $contact->update();

        return redirect('/contacts');
    }

    public function destroy(Contact $contact) {
        $contact->delete();
        session()->flash('alert', "Contact $contact->name deleted successfuly");

        return redirect('/contacts');
    }
}
