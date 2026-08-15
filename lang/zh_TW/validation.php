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

    'accepted' => ':attribute 必須接受。',
    'accepted_if' => ':other 為 :value 時，:attribute 必須接受。',
    'active_url' => ':attribute 必須是有效的網址。',
    'after' => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal' => ':attribute 必須是 :date 當天或之後的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、數字、破折號和底線。',
    'alpha_num' => ':attribute 只能包含字母和數字。',
    'any_of' => ':attribute 無效。',
    'array' => ':attribute 必須是陣列。',
    'array_keys' => ':attribute 只能包含下列鍵值：:values。',
    'ascii' => ':attribute 只能包含單一位元組的英數字元與符號。',
    'base64' => ':attribute 必須是有效的 Base64 字串。',
    'before' => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必須是 :date 當天或之前的日期。',
    'between' => [
        'array' => ':attribute 必須介於 :min 到 :max 個項目之間。',
        'file' => ':attribute 必須介於 :min 到 :max KB 之間。',
        'numeric' => ':attribute 必須介於 :min 到 :max 之間。',
        'string' => ':attribute 必須介於 :min 到 :max 個字元之間。',
    ],
    'boolean' => ':attribute 必須是 true 或 false。',
    'can' => ':attribute 包含未授權的值。',
    'confirmed' => ':attribute 確認欄位不相符。',
    'contains' => ':attribute 缺少必要值。',
    'current_password' => '密碼不正確。',
    'date' => ':attribute 必須是有效的日期。',
    'date_equals' => ':attribute 必須是等於 :date 的日期。',
    'date_format' => ':attribute 必須符合格式 :format。',
    'decimal' => ':attribute 必須有 :decimal 位小數。',
    'declined' => ':attribute 必須拒絕。',
    'declined_if' => ':other 為 :value 時，:attribute 必須拒絕。',
    'different' => ':attribute 與 :other 必須不同。',
    'digits' => ':attribute 必須是 :digits 位數字。',
    'digits_between' => ':attribute 必須介於 :min 到 :max 位數字。',
    'dimensions' => ':attribute 的圖片尺寸無效。',
    'distinct' => ':attribute 有重複的值。',
    'doesnt_contain' => ':attribute 不得包含下列任一項：:values。',
    'doesnt_end_with' => ':attribute 不得以下列任一項結尾：:values。',
    'doesnt_start_with' => ':attribute 不得以下列任一項開頭：:values。',
    'email' => ':attribute 必須是有效的 Email 地址。',
    'encoding' => ':attribute 必須以 :encoding 編碼。',
    'ends_with' => ':attribute 必須以下列任一項結尾：:values。',
    'enum' => '所選的 :attribute 無效。',
    'exists' => '所選的 :attribute 無效。',
    'extensions' => ':attribute 必須具有下列副檔名之一：:values。',
    'file' => ':attribute 必須是檔案。',
    'filled' => ':attribute 必須有值。',
    'gt' => [
        'array' => ':attribute 必須多於 :value 個項目。',
        'file' => ':attribute 必須大於 :value KB。',
        'numeric' => ':attribute 必須大於 :value。',
        'string' => ':attribute 必須多於 :value 個字元。',
    ],
    'gte' => [
        'array' => ':attribute 必須有 :value 個項目或更多。',
        'file' => ':attribute 必須大於或等於 :value KB。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string' => ':attribute 必須大於或等於 :value 個字元。',
    ],
    'hex_color' => ':attribute 必須是有效的十六進位顏色碼。',
    'image' => ':attribute 必須是圖片。',
    'in' => '所選的 :attribute 無效。',
    'in_array' => ':attribute 必須存在於 :other 中。',
    'in_array_keys' => ':attribute 必須包含下列鍵值中的至少一項：:values。',
    'integer' => ':attribute 必須是整數。',
    'ip' => ':attribute 必須是有效的 IP 位址。',
    'ipv4' => ':attribute 必須是有效的 IPv4 位址。',
    'ipv6' => ':attribute 必須是有效的 IPv6 位址。',
    'json' => ':attribute 必須是有效的 JSON 字串。',
    'list' => ':attribute 必須是清單。',
    'lowercase' => ':attribute 必須全為小寫。',
    'lt' => [
        'array' => ':attribute 必須少於 :value 個項目。',
        'file' => ':attribute 必須小於 :value KB。',
        'numeric' => ':attribute 必須小於 :value。',
        'string' => ':attribute 必須少於 :value 個字元。',
    ],
    'lte' => [
        'array' => ':attribute 不得多於 :value 個項目。',
        'file' => ':attribute 必須小於或等於 :value KB。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string' => ':attribute 必須小於或等於 :value 個字元。',
    ],
    'mac_address' => ':attribute 必須是有效的 MAC 位址。',
    'max' => [
        'array' => ':attribute 不得多於 :max 個項目。',
        'file' => ':attribute 不得大於 :max KB。',
        'numeric' => ':attribute 不得大於 :max。',
        'string' => ':attribute 不得多於 :max 個字元。',
    ],
    'max_digits' => ':attribute 不得多於 :max 位數字。',
    'mimes' => ':attribute 必須是 :values 類型的檔案。',
    'mimetypes' => ':attribute 必須是 :values 類型的檔案。',
    'min' => [
        'array' => ':attribute 必須至少有 :min 個項目。',
        'file' => ':attribute 必須至少 :min KB。',
        'numeric' => ':attribute 必須至少為 :min。',
        'string' => ':attribute 必須至少有 :min 個字元。',
    ],
    'min_digits' => ':attribute 必須至少有 :min 位數字。',
    'missing' => ':attribute 必須缺少。',
    'missing_if' => ':other 為 :value 時，:attribute 必須缺少。',
    'missing_unless' => '除非 :other 為 :value，否則 :attribute 必須缺少。',
    'missing_with' => '當 :values 存在時，:attribute 必須缺少。',
    'missing_with_all' => '當 :values 都存在時，:attribute 必須缺少。',
    'multiple_of' => ':attribute 必須是 :value 的倍數。',
    'not_in' => '所選的 :attribute 無效。',
    'not_regex' => ':attribute 的格式無效。',
    'numeric' => ':attribute 必須是數字。',
    'password' => [
        'letters' => ':attribute 必須至少包含一個字母。',
        'mixed' => ':attribute 必須至少包含一個大寫和一個小寫字母。',
        'numbers' => ':attribute 必須至少包含一個數字。',
        'symbols' => ':attribute 必須至少包含一個符號。',
        'uncompromised' => '給定的 :attribute 已出現在資料外洩事件中，請選擇不同的 :attribute。',
    ],
    'present' => ':attribute 必須存在。',
    'present_if' => ':other 為 :value 時，:attribute 必須存在。',
    'present_unless' => '除非 :other 為 :value，否則 :attribute 必須存在。',
    'present_with' => '當 :values 存在時，:attribute 必須存在。',
    'present_with_all' => '當 :values 都存在時，:attribute 必須存在。',
    'prohibited' => ':attribute 欄位禁止使用。',
    'prohibited_if' => ':other 為 :value 時，:attribute 欄位禁止使用。',
    'prohibited_if_accepted' => ':other 被接受時，:attribute 欄位禁止使用。',
    'prohibited_if_declined' => ':other 被拒絕時，:attribute 欄位禁止使用。',
    'prohibited_unless' => '除非 :other 為 :values，否則 :attribute 欄位禁止使用。',
    'prohibits' => ':attribute 欄位禁止 :other 存在。',
    'regex' => ':attribute 的格式無效。',
    'required' => ':attribute 欄位必填。',
    'required_array_keys' => ':attribute 必須包含下列項目：:values。',
    'required_if' => ':other 為 :value 時，:attribute 欄位必填。',
    'required_if_accepted' => ':other 被接受時，:attribute 欄位必填。',
    'required_if_declined' => ':other 被拒絕時，:attribute 欄位必填。',
    'required_unless' => '除非 :other 為 :values，否則 :attribute 欄位必填。',
    'required_with' => '當 :values 存在時，:attribute 欄位必填。',
    'required_with_all' => '當 :values 都存在時，:attribute 欄位必填。',
    'required_without' => '當 :values 不存在時，:attribute 欄位必填。',
    'required_without_all' => '當 :values 全部不存在時，:attribute 欄位必填。',
    'same' => ':attribute 必須與 :other 相同。',
    'size' => [
        'array' => ':attribute 必須包含 :size 個項目。',
        'file' => ':attribute 必須是 :size KB。',
        'numeric' => ':attribute 必須是 :size。',
        'string' => ':attribute 必須是 :size 個字元。',
    ],
    'starts_with' => ':attribute 必須以下列任一項開頭：:values。',
    'string' => ':attribute 必須是字串。',
    'timezone' => ':attribute 必須是有效的時區。',
    'unique' => ':attribute 已被使用。',
    'uploaded' => ':attribute 上傳失敗。',
    'uppercase' => ':attribute 必須全為大寫。',
    'url' => ':attribute 必須是有效的網址。',
    'ulid' => ':attribute 必須是有效的 ULID。',
    'uuid' => ':attribute 必須是有效的 UUID。',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
