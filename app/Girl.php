<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Girl extends Model
{
	protected $fillable = ['user', 'email', 'name', 'birth', 'location', 'weight', 'height', 'hair', 'eyes', 'religion', 'education', 'maritial', 'children', 'smoking', 'alcohol', 'english', 'prefferFrom', 'prefferTo', 'info', 'firstLetterSubject', 'firstLetter', 'forAdminName', 'forAdminSurname', 'forAdminFathersName', 'forAdminPhoneNumber'];


}