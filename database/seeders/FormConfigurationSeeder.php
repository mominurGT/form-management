<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormConfiguration;

class FormConfigurationSeeder extends Seeder
{
    public function run()
    {
        
        $contactFormJson = '{
            "title": "Contact Form",
            "method": "POST",
            "action": "/submit",
            "fields": [
                {
                    "type": "text",
                    "name": "name",
                    "label": "Name",
                    "placeholder": "Enter your name",
                    "required": true
                },
                {
                    "type": "email",
                    "name": "email",
                    "label": "Email",
                    "placeholder": "Enter your email",
                    "required": true
                },
                {
                    "type": "textarea",
                    "name": "message",
                    "label": "Message",
                    "placeholder": "Enter your message",
                    "required": true
                }
            ]
        }';

        $testFormData = '{
            "title": "Test Form",
            "method": "POST",
            "action": "/submit",
            "fields": [
                {
                    "name": "full_name",
                    "label": "Full Name",
                    "type": "text",
                    "placeholder": "John Doe",
                    "required": true,
                    "minLength": 3,
                    "maxLength": 50
                },
                {
                    "name": "email",
                    "label": "Email Address",
                    "type": "email",
                    "placeholder": "you@example.com",
                    "required": true
                },
                {
                    "name": "password",
                    "label": "Password",
                    "type": "password",
                    "placeholder": "••••••••",
                    "required": true,
                    "minLength": 8
                },
                {
                    "name": "age",
                    "label": "Age",
                    "type": "number",
                    "placeholder": "30",
                    "required": false
                },
                {
                    "name": "dob",
                    "label": "Date of Birth",
                    "type": "date",
                    "required": true
                },
                {
                    "name": "bio",
                    "label": "Short Bio",
                    "type": "textarea",
                    "placeholder": "Tell us something about yourself...",
                    "required": false,
                    "maxLength": 300
                },
                {
                    "name": "subscribe",
                    "label": "Subscribe to newsletter",
                    "type": "checkbox",
                    "required": false
                },
                {
                    "name": "gender",
                    "label": "Gender",
                    "type": "radio",
                    "required": true,
                    "options": [
                        { "label": "Male", "value": "male" },
                        { "label": "Female", "value": "female" },
                        { "label": "Other", "value": "other" }
                    ]
                },
                {
                    "name": "country",
                    "label": "Country",
                    "type": "select",
                    "required": true,
                    "options": [
                        { "label": "United States", "value": "us" },
                        { "label": "India", "value": "in" },
                        { "label": "Bangladesh", "value": "bd" }
                    ]
                }
            ]
        }';

        FormConfiguration::create([
            'configuration' => $contactFormJson,
        ]);

        FormConfiguration::create([
            'configuration' => $testFormData,
        ]);
    }
}
