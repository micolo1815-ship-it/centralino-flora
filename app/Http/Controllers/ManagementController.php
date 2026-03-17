<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function addLocation()
    {
        return view('auth.add-location');
    }

    public function addNewOfficers()
    {
        return view('auth.add-new-officers');       
    }
    public function addTree()
    {
        return view('auth.add-tree');
    }
    public function editCurrentOfficers()
    {
        return view('auth.edit-current-officers');
    }
    public function editLocation()
    {
        return view('auth.edit-location');
    }
    public function editPrevOfficers()
    {
        return view('auth.edit-prev-officers');
    }
    public function editTree()
    {
        return view('auth.edit-tree');
    }
}
