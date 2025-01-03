<?php

return [
    'accepted'             => 'Le champ :attribute doit être accepté.',
    'active_url'           => "L'URL :attribute n'est pas valide.",
    'after'                => "La date :attribute doit être postérieure à :date.",
    'alpha'                => "Le champ :attribute ne peut contenir que des lettres.",
    'alpha_dash'           => "Le champ :attribute ne peut contenir que des lettres, des chiffres et des tirets.",
    'alpha_num'            => "Le champ :attribute ne peut contenir que des lettres et des chiffres.",
    'array'                => "Le champ :attribute doit être un tableau.",
    'before'               => "La date :attribute doit être antérieure à :date.",
    'between'              => [
        'numeric' => "Le champ :attribute doit être entre :min et :max.",
        'file'    => "Le fichier :attribute doit être entre :min et :max kilobytes.",
        'string'  => "Le champ :attribute doit être entre :min et :max caractères.",
        'array'   => "Le tableau :attribute doit avoir entre :min et :max éléments.",
    ],
    'boolean'              => "Le champ :attribute doit être vrai ou faux.",
    'confirmed'            => "La confirmation de :attribute ne correspond pas.",
    'date'                 => "Le champ :attribute n'est pas une date valide.",
    'date_equals'          => "Le champ :attribute doit être une date égale à :date.",
    'date_format'          => "Le champ :attribute ne correspond pas au format :format.",
    'different'            => "Les champs :attribute et :other doivent être différents.",
    'digits'               => "Le champ :attribute doit être composé de :digits chiffres.",
    'digits_between'       => "Le champ :attribute doit être entre :min et :max chiffres.",
    'dimensions'           => "Le champ :attribute a des dimensions d'image invalides.",
    'distinct'             => "Le champ :attribute a une valeur en double.",
    'email'                => "L'adresse e-mail :attribute doit être une adresse valide.",
    'exists'               => "Le champ sélectionné :attribute est invalide.",
    'file'                 => "Le champ :attribute doit être un fichier.",
    'filled'               => "Le champ :attribute doit avoir une valeur.",
    'gt'                   => [
        'numeric' => "Le champ :attribute doit être supérieur à :value.",
        'file'    => "Le fichier :attribute doit être supérieur à :value kilobytes.",
        'string'  => "Le champ :attribute doit être supérieur à :value caractères.",
        'array'   => "Le tableau :attribute doit avoir plus de :value éléments.",
    ],
    'gte'                  => [
        'numeric' => "Le champ :attribute doit être supérieur ou égal à :value.",
        'file'    => "Le fichier :attribute doit être supérieur ou égal à :value kilobytes.",
        'string'  => "Le champ :attribute doit être supérieur ou égal à :value caractères.",
        'array'   => "Le tableau :attribute doit avoir :value éléments ou plus.",
    ],
    'image'                => "Le champ :attribute doit être une image.",
    'in'                   => "Le champ :attribute sélectionné est invalide.",
    'in_array'             => "Le champ :attribute n'existe pas dans :other.",
    'integer'              => "Le champ :attribute doit être un entier.",
    'ip'                   => "Le champ :attribute doit être une adresse IP valide.",
    'ipv4'                 => "Le champ :attribute doit être une adresse IPv4 valide.",
    'ipv6'                 => "Le champ :attribute doit être une adresse IPv6 valide.",
    'json'                 => "Le champ :attribute doit être une chaîne JSON valide.",
    'lt'                   => [
        'numeric' => "Le champ :attribute doit être inférieur à :value.",
        'file'    => "Le fichier :attribute doit être inférieur à :value kilobytes.",
        'string'  => "Le champ :attribute doit être inférieur à :value caractères.",
        'array'   => "Le tableau :attribute doit avoir moins de :value éléments.",
    ],
    'lte'                  => [
        'numeric' => "Le champ :attribute doit être inférieur ou égal à :value.",
        'file'    => "Le fichier :attribute doit être inférieur ou égal à :value kilobytes.",
        'string'  => "Le champ :attribute doit être inférieur ou égal à :value caractères.",
        'array'   => "Le tableau :attribute doit avoir :value éléments ou moins.",
    ],
    'max'                  => [
        'numeric' => "Le champ :attribute ne peut être supérieur à :max.",
        'file'    => "Le fichier :attribute ne peut être supérieur à :max kilobytes.",
        'string'  => "Le champ :attribute ne peut pas avoir plus de :max caractères.",
        'array'   => "Le tableau :attribute ne peut pas avoir plus de :max éléments.",
    ],
    'mimes'                => "Le fichier :attribute doit être de type :values.",
    'mimetypes'           => "Le fichier :attribute doit être de type :values.",
    'min'                  => [
        'numeric' => "Le champ :attribute doit être au moins :min.",
        'file'    => "Le fichier :attribute doit être d'au moins :min kilobytes.",
        'string'  => "Le champ :attribute doit contenir au moins :min caractères.",
        'array'   => "Le tableau :attribute doit avoir au moins :min éléments.",
    ],
    'not_in'               => "Le champ :attribute sélectionné est invalide.",
    'numeric'              => "Le champ :attribute doit être un nombre.",
    'present'              => "Le champ :attribute doit être présent.",
    'regex'                => "Le format du champ :attribute est invalide.",
    'required'             => "Le champ :attribute est obligatoire.",
    'required_if'          => "Le champ :attribute est obligatoire lorsque :other est :value.",
    'required_unless'      => "Le champ :attribute est obligatoire à moins que :other ne soit dans :values.",
    'required_with'        => "Le champ :attribute est obligatoire lorsque :values est présent.",
    'required_with_all'    => "Le champ :attribute est obligatoire lorsque :values sont présents.",
    'required_without'     => "Le champ :attribute est obligatoire lorsque :values n'est pas présent.",
    'required_without_all' => "Le champ :attribute est obligatoire lorsque aucun des :values n'est présent.",
    'same'                 => "Les champs :attribute et :other doivent être identiques.",
    'size'                 => [
        'numeric' => "Le champ :attribute doit être :size.",
        'file'    => "Le fichier :attribute doit être de :size kilobytes.",
        'string'  => "Le champ :attribute doit être de :size caractères.",
        'array'   => "Le tableau :attribute doit contenir :size éléments.",
    ],
    'string'               => "Le champ :attribute doit être une chaîne de caractères.",
    'timezone'             => "Le champ :attribute doit être un fuseau horaire valide.",
    'unique'               => "Le champ :attribute a déjà été pris.",
    'uploaded'             => "Le fichier :attribute n'a pas pu être téléchargé.",
    'url'                  => "Le format de l'URL :attribute est invalide.",
    'uuid'                 => "Le champ :attribute doit être un UUID valide.",
    'custom'               => [
        // Messages d'erreur personnalisés pour certaines règles
    ],
    'attributes'           => [
        'name' => 'nom',
        'email' => 'adresse e-mail',
        'password' => 'mot de passe',
    ],
];
