<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageBase64 implements Rule
{
    public $mimeTypes = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($mimeTypes = ['png', 'jpg', 'jpeg', 'gif'])
    {
        $this->mimeTypes = (is_array($mimeTypes))?$mimeTypes:[];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = \Illuminate\Filesystem\Filesystem::formatClearBase64String($value);

        if(empty($value))
            return false;

        $binaryData = base64_decode($value);

        // temporarily store the decoded data on the filesystem to be able to pass it to the fileAdder
        $tmpFile = tempnam(sys_get_temp_dir(), 'medialibrary');
        file_put_contents($tmpFile, $binaryData);

        $validatorData = ['file' => new \Illuminate\Http\File($tmpFile)];

        // Check the MimeTypes
        $validation = \Validator::make($validatorData,
            ['file' => 'mimes:' . implode(',', $this->mimeTypes)]
        );

        //return results
        return !$validation->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field is not valid image.';
    }
}
