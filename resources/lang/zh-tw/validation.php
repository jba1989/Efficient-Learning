<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 必需被接受',
    'active_url'           => ':attribute 不是正常的網址格式',
    'after'                => ':attribute 時間必須在 :date 之後',
    'after_or_equal'       => ':attribute 時間必須在 :date ，或是之後',
    'alpha'                => ':attribute 只可用大小寫英文',
    'alpha_dash'           => ':attribute 不可輸入特殊字元',
    'alpha_num'            => ':attribute 只可用英文或數字',
    'array'                => ':attribute 必須是個陣列',
    'before'               => ':attribute 時間必需在 :date 之前',
    'before_or_equal'      => ':attribute 時間必須在 :date 或是之後',
    'between'              => [
        'numeric' => ':attribute 數字大小需介於 :min 和 :max.',
        'file'    => ':attribute 檔案大小需介於 :min 和 :max kb',
        'string'  => ':attribute 文字長度需介於 :min 和 :max 個字',
        'array'   => ':attribute 陣列元素需介於 :min 和 :max 個',
    ],
    'boolean'              => ':attribute field must be true or false.',
    'confirmed'            => ':attribute 兩次輸入的內容不同',
    'date'                 => ':attribute 錯誤的時間格式',
    'date_format'          => ':attribute 必須是 :format 格式',
    'different'            => ':attribute 和 :other 不能相同',
    'digits'               => ':attribute must be :digits digits.',
    'digits_between'       => ':attribute must be between :min and :max digits.',
    'dimensions'           => ':attribute has invalid image dimensions.',
    'distinct'             => ':attribute field has a duplicate value.',
    'email'                => ':attribute 必需是正常的email格式',
    'exists'               => 'selected :attribute 不存在',
    'file'                 => ':attribute 必需是個檔案格式',
    'filled'               => ':attribute 必需有值',
    'gt'                   => [
        'numeric' => ':attribute must be greater than :value.',
        'file'    => ':attribute must be greater than :value kilobytes.',
        'string'  => ':attribute must be greater than :value characters.',
        'array'   => ':attribute must have more than :value items.',
    ],
    'gte'                  => [
        'numeric' => ':attribute must be greater than or equal :value.',
        'file'    => ':attribute must be greater than or equal :value kilobytes.',
        'string'  => ':attribute must be greater than or equal :value characters.',
        'array'   => ':attribute must have :value items or more.',
    ],
    'image'                => ':attribute 檔案格式必需是圖片',
    'in'                   => 'selected :attribute is invalid.',
    'in_array'             => ':attribute field does not exist in :other.',
    'integer'              => ':attribute 必需是整數',
    'ip'                   => ':attribute must be a valid IP address.',
    'ipv4'                 => ':attribute must be a valid IPv4 address.',
    'ipv6'                 => ':attribute must be a valid IPv6 address.',
    'json'                 => ':attribute 必需是JSON格式',
    'lt'                   => [
        'numeric' => ':attribute must be less than :value.',
        'file'    => ':attribute must be less than :value kilobytes.',
        'string'  => ':attribute must be less than :value characters.',
        'array'   => ':attribute must have less than :value items.',
    ],
    'lte'                  => [
        'numeric' => ':attribute must be less than or equal :value.',
        'file'    => ':attribute must be less than or equal :value kilobytes.',
        'string'  => ':attribute must be less than or equal :value characters.',
        'array'   => ':attribute must not have more than :value items.',
    ],
    'max'                  => [
        'numeric' => ':attribute 必需小於 :max',
        'file'    => ':attribute 必需小於 :max kb',
        'string'  => ':attribute 必需小於 :max 個字',
        'array'   => ':attribute 陣列元素需必需小於 :max 個',
    ],
    'mimes'                => ':attribute must be a file of type: :values.',
    'mimetypes'            => ':attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute 必需大於 :min',
        'file'    => ':attribute 必需大於 :min kb',
        'string'  => ':attribute 必需大於 :min 個字',
        'array'   => ':attribute 陣列元素需必需大於 :min 個',
    ],
    'not_in'               => 'selected :attribute is invalid.',
    'not_regex'            => ':attribute format is invalid.',
    'numeric'              => ':attribute 只能輸入數字',
    'present'              => ':attribute field must be present.',
    'regex'                => ':attribute 格式錯誤',
    'required'             => ':attribute 必需要有值',
    'required_if'          => ':attribute field is required when :other is :value.',
    'required_unless'      => ':attribute field is required unless :other is in :values.',
    'required_with'        => ':attribute field is required when :values is present.',
    'required_with_all'    => ':attribute field is required when :values is present.',
    'required_without'     => ':attribute field is required when :values is not present.',
    'required_without_all' => ':attribute field is required when none of :values are present.',
    'same'                 => ':attribute 和 :other 必需相同',
    'size'                 => [
        'numeric' => ':attribute must be :size.',
        'file'    => ':attribute must be :size kilobytes.',
        'string'  => ':attribute must be :size characters.',
        'array'   => ':attribute must contain :size items.',
    ],
    'string'               => ':attribute 資料型態必須是string',
    'timezone'             => ':attribute 時區不正確',
    'unique'               => ':attribute 已經被使用',
    'uploaded'             => ':attribute 檔案上傳失敗',
    'url'                  => ':attribute 網址格式錯誤',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
