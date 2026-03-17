<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfficerRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization as needed
    }

    public function rules()
    {
        $positions = [
            'program_chair'  => 'Program Chair',
            'adviser'        => 'Adviser',
            'president'      => 'President',
            'viceP_internal' => 'Vice President Internal',
            'viceP_external' => 'Vice President External',
            'secretary'      => 'Secretary',
            'treasurer'      => 'Treasurer',
            'auditor'        => 'Auditor',
            'pro'            => 'PRO',
            '1st_rep'        => '1st Year Representative',
            '2nd_rep'        => '2nd Year Representative',
            '3rd_rep'        => '3rd Year Representative',
            '4th_rep'        => '4th Year Representative',
        ];

        $rules = [
            'school_year' => [
                'required',
                'regex:/^\d{4}-\d{4}$/',
                function ($attribute, $value, $fail) {
                    [$startYear, $endYear] = explode('-', $value);
                    if ((int)$endYear !== (int)$startYear + 1) {
                        $fail('The school year must be consecutive years, e.g., 2025-2026.');
                    }
                },
                \Illuminate\Validation\Rule::unique('officers', 'school_year'),
            ],
            'retain_same_person_program_chair' => 'nullable|boolean',
            'retain_same_person_adviser'       => 'nullable|boolean',
        ];

        foreach ($positions as $key => $positionName) {
            $rules["{$key}_firstname"]      = 'required|string|max:255';
            $rules["{$key}_middle_initial"] = 'nullable|string|max:1';
            $rules["{$key}_lastname"]       = 'required|string|max:255';
            $rules["{$key}_image"]          = 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120';

            // ✅ Email unique among ACTIVE users only
            // If retained, skip uniqueness check entirely since same person keeps their account
            $isRetainKey = in_array($key, ['program_chair', 'adviser']);

            $rules["{$key}_email"] = [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($key, $isRetainKey) {
                    $isRetained = $this->input("retain_same_person_{$key}");

                    // ✅ Skip check if this is a retained special officer
                    if ($isRetainKey && $isRetained) {
                        return;
                    }

                    // ✅ Only block if email belongs to an ACTIVE user
                    $exists = \App\Models\User::where('email', $value)
                        ->where('status', 'active')
                        ->exists();

                    if ($exists) {
                        $fail("The email {$value} is already used by an active user.");
                    }
                },
            ];
        }

        return $rules;
    }
}
