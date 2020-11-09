<?php

return [
    'GoToForum'   => 'Przejdź do prezentacji forum',
    'QrCode'      => 'Możesz zeskanować ten kod, aby rzucać kostką w aplikacji.',
    'Description' => ' Uzupelnij poniższe pola <small>(opis rzutu jest polem opcjonalnym)</small>, 
            a następnie wybierz jedną z kostek i wpisz ich ilość <small>(maksymalnie 10)</small>. 
            Po wypełnieniu pól, możesz na dole strony zobaczyć jak rzut, wraz z opisem, będzie się prezentował. 
            Gdy naciśniesz przycisk <em>Losuj!</em>, skrypt sprawdzi poprawność danych, a następnie rzut zostanie wykonany 
            i wyświetlony jego rezultat.<br> 
            Po dokonaniu rzutu, możliwe jest dodanie do niego kolejnego.
            <br>
             Pamiętaj, aby <strong>jednoznacznie opisać rzut kostką</strong>, co uwiarygodni Twoją dobrą wolę 
            i odsunie podejrzenia o próbie przeinaczenia przeznaczenia rzutu. <em>Nie kuś złego losu!</em>',
    'Rolling'     => 'Rzuć kostkami!',
    'Example'     => [
        'Title' => 'Rzut poglądowy <em>(tak będzie wyglądał, sam los jest przykładowy)</em>'
    ],
    'Form'        => [
        'ConfigureEmpty'    => 'Wybierz typ kostki i ich ilość',
        'ConfigureSelected' => 'Wybrana kostka: ',
        'SetAsCustom'       => 'Podaj ilośc oczek',
        'Name'            => [
            'Label'       => 'Nadaj tytuł temu rzutowi:',
            'Placeholder' => 'np.: Rzut na powodzenie rzucania zaklęcia X',
        ],
        'Description'     => [
            'Label'       => 'Podaj szczegółowy opis rzutu:',
            'Placeholder' => 'np.: co oznacza wyższy wynik losowania? Jeśli wybierasz kilka kostek, co wyznacza każda z nich?',
        ],
        'CountOfDices'    => [
            'Label'       => 'Liczba kostek:',
            'Placeholder' => 'Wprowadź liczbe kostek',
        ],
    ]
];
