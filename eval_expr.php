<?php




function parsing($infix)
{

    $precedence = array(
        '(' => 1,
        '2' => 1,
        '+' => 2,
        '-' => 2,
        '*' => 3,
        '/' => 3,
        '%' => 3,
    );



    // Gestion du parsing de nombres a plusieurs chiffres et virgules et s'arrete lorsqu'il y a un symbole 
    $tokentab = [];
    for ($i = 0; isset($infix[$i]); $i++) {
        $result = "";
        if (preg_match("/\d+/", $infix[$i])) {
            $result .= $infix[$i];  // il concatene si c'est un nombre

            while (isset($infix[$i + 1]) && preg_match("/[0-9.]+/", $infix[$i + 1])) {
                $result .= $infix[++$i];
            }
            array_push($tokentab, $result);
        } else {
            array_push($tokentab, $infix[$i]);
        }
    }
    // var_dump($tokentab);
    // print_r($result);



    //  on stock les operands dans un tableau et les operateurs dans un autre tableau pour pouvoir les retranscrire en NPI dans le $SORTIE qui serait notre calcul final en NPI
    $sortie = [];
    $symboletab = [];
    for ($i = 0; $i < count($tokentab); $i++) {
        if (preg_match("/\d+/", $tokentab[$i])) {
            array_push($sortie, $tokentab[$i]);
        } else {
            if ($tokentab[$i] == "(") {
                array_push($symboletab, $tokentab[$i]);
            } elseif ($tokentab[$i] == ")") {
                while (end($symboletab) !== "(") {
                    $sortie[] = array_pop($symboletab);
                }
                array_pop($symboletab);
            } else {

                while (!empty($symboletab) && $precedence[$tokentab[$i]] <= $precedence[end($symboletab)]) {

                    $sortie[] = array_pop($symboletab); // push le derniere elements de symboletab dans la sortie 

                }
                array_push($symboletab, $tokentab[$i]);
            }
        }
    }

    while (!empty($symboletab)) {
        $sortie[] =  array_pop($symboletab);
        // var_dump($sortie);
    }

    return implode(" ", $sortie);
}


// parsing("12.345+30.00");







function calculate($operator, $op1, $op2)
{

    switch ($operator) {
        case '+':
            $resultat = $op1 + $op2;
            break;

        case '-':
            $resultat = $op1 - $op2;
            break;

        case '*':
            $resultat = $op1 * $op2;
            break;

        case '/':
            $resultat = $op1 / $op2;
            break;

        case '%':
            $resultat = $op1 % $op2;
            break;

        default:
            # code...
            break;
    }
    return $resultat;
}







function eval_expr($expr)
{
    $expr = parsing($expr);
    $resultat = 0;
    $pile = [];
    $elements = explode(" ", $expr);
    foreach ($elements as $value) {

        if (preg_match('/[-+\/*%]/', $value)) {
            $op1 = array_pop($pile);
            $op2 = array_pop($pile);
            $resultat = calculate($value, $op2, $op1);
            array_push($pile, $resultat);
        } else {
            array_push($pile, $value);
        }
        // var_dump($pile);
        // print($value);
    }
    // print_r($resultat . PHP_EOL);
    // var_dump($resultat);
    return $resultat;
}

// eval_expr("44*(4*(2+2))");
