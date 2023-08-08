<?php
class Automaton {

    protected array $transitions = [
        'q0' => [
            'i' => 'q3',
            'f' => 'q5',
            'w' => 'q8',
            's' => 'q13',
            'd' => 'q19',
            'e' => 'q21',
            'p' => 'q25',
            'r' => 'q30',
            'variable' => 'q1',
            'integer' => 'q2'
        ],
        'q1' => [
            'variable' => 'q1'
        ],
        'q2' => [
            'integer' => 'q2'
        ],
        'q3' => [
            'f' => 'q4',
            'variable' => 'q1'
        ],
        'q4' => [
            'variable' => 'q1'
        ],
        'q5' => [
            'o' => 'q6',
            'variable' => 'q1'
        ],
        'q6' => [
            'r' => 'q7',
            'variable' => 'q1'
        ],
        'q7' => [
            'e' => 'q34',
            'variable' => 'q1'
        ],
        'q8' => [
            'h' => 'q9',
            'variable' => 'q1'
        ],
        'q9' => [
            'i' => 'q10',
            'variable' => 'q1'
        ],
        'q10' => [
            'l' => 'q11',
            'variable' => 'q1'
        ],
        'q11' => [
            'e' => 'q4',
            'variable' => 'q1'
        ],
        'q13' => [
            'w' => 'q14',
            'variable' => 'q1'
        ],
        'q14' => [
            'i' => 'q15',
            'variable' => 'q1'
        ],
        'q15' => [
            't' => 'q16',
            'variable' => 'q1'
        ],
        'q16' => [
            'c' => 'q17',
            'variable' => 'q1'
        ],
        'q17' => [
            'h' => 'q4',
            'variable' => 'q1'
        ],
        'q19' => [
            'o' => 'q4',
            'variable' => 'q1'
        ],
        'q21' => [
            'l' => 'q22',
            'variable' => 'q1'
        ],
        'q22' => [
            's' => 'q23',
            'variable' => 'q1'
        ],
        'q23' => [
            'e' => 'q4',
            'variable' => 'q1'
        ],
        'q25' => [
            'r' => 'q26',
            'variable' => 'q1'
        ],
        'q26' => [
            'i' => 'q27',
            'variable' => 'q1'
        ],
        'q27' => [
            'n' => 'q28',
            'variable' => 'q1'
        ],
        'q28' => [
            't' => 'q4',
            'variable' => 'q1'
        ],
        'q30' => [
            'e' => 'q31',
            'variable' => 'q1'
        ],
        'q31' => [
            'a' => 'q32',
            'variable' => 'q1'
        ],
        'q32' => [
            'd' => 'q4',
            'variable' => 'q1'
        ],
        'q34' => [
            'a' => 'q35',
            'variable' => 'q1'
        ],
        'q35' => [
            'c' => 'q36',
            'variable' => 'q1'
        ],
        'q36' => [
            'h' => 'q4',
            'variable' => 'q1'
        ],
    ];

    private array $chars = [];

    private array $variable = [];

    private array $constant = [];

    public array $reservedWords = [
        'if',
        'for',
        'foreach',
        'while',
        'do',
        'switch',
        'print',
        'read'
    ];

    public array $symbols = [
        '+' => 'PLUS',
        '-' => 'MINUS',
        '>' => 'BIGGER_THAN',
        '<' => 'LESS_THAN',
        '=' => 'EQUAL',
        '*' => 'MULTIPLY',
        '/' => 'DIVIDED',
        '%' => 'REST',
        '(' => 'OPEN_PARENTHESES',
        ')' => 'CLOSE_PARENTHESES',
        '[' => 'OPEN_BRACKETS',
        ']' => 'CLOSE_BRACKETS',
        '{' => 'OPEN_BRACE',
        '}' => 'CLOSE_BRACE',
    ];

    public function __construct(string $string)
    {
        $this->setChars($string);
        foreach ($this->reservedWords as $reservedWord) {
            $this->{$reservedWord} = [];
        }
        foreach ($this->symbols as $reservedWord) {
            $this->{$reservedWord} = [];
        }
    }

    public function getChars(): array
    {
        return $this->chars;
    }

    public function setChars(string $chars): void
    {
        $this->chars = str_split(mb_strtolower(trim($chars)));
    }

    public function getVariable(): array
    {
        return $this->variable;
    }

    public function setVariable(string $variable, int $pos): void
    {
        $this->variable[] = [
            'token' => 'VARIABLE',
            'lexeme' => $variable,
            'initial' => $pos - strlen($variable),
            'final' => $pos,
        ];
    }

    public function getConstant(): array
    {
        return $this->constant;
    }

    public function setConstant(string $constant, int $pos): void
    {
        $this->constant[] = [
            'token' => 'CONSTANT',
            'lexeme' => $constant,
            'initial' => $pos - strlen($constant),
            'final' => $pos,
        ];
    }

    private function setRelativeLexeme(string $lexeme, int $pos): void
    {
        $this->{$lexeme}[] = [
            'token' => strtoupper($lexeme),
            'lexeme' => $lexeme,
            'initial' => $pos - strlen($lexeme),
            'final' => $pos,
        ];
    }

    private function setSymbol(string $symbol, int $pos): void
    {
        $this->{$this->symbols[$symbol]}[] = [
            'token' => $this->symbols[$symbol],
            'lexeme' => $symbol,
            'initial' => $pos - 1,
            'final' => $pos,
        ];
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }

    public function test(): array
    {
        $transitions = $this->getTransitions();
        $chars = $this->getChars();
        $currentString = '';
        $currentState = 'q0';
        $lastIndex = count($chars) - 1;

        foreach ($chars as $index => $char) {
            if (in_array($char, $symbols = array_keys($this->symbols)) || ctype_space($char)) {
                if ($currentState == 'q1') {
                    $this->setVariable($currentString, $index);
                } else if ($currentState == 'q2') {
                    $this->setConstant($currentString, $index);
                } else if (in_array($currentState, ['q4', 'q7'])) {
                    $this->setRelativeLexeme($currentString, $index);
                }

                if (in_array($char, $symbols)) {
                    $this->setSymbol($char, $index);
                }

                $currentString = '';
                $currentState = 'q0';
            } else if (array_key_exists($char, $transitions[$currentState])) {
                $currentString .= $char;
                $currentState = $transitions[$currentState][$char];
            } else if (is_numeric($char) && array_key_exists('integer', $transitions[$currentState])) {
                $currentString .= $char;
                $currentState = $transitions[$currentState]['integer'];
            } else if (array_key_exists('variable', $transitions[$currentState])) {
                $currentString .= $char;
                $currentState = $transitions[$currentState]['variable'];
            } else {
                return [
                    'bool' => false,
                    'message' => "Error in index $index: $currentString Is a Invalid Expression! You cannot initialize a variable with a number."
                ];
            }

            if ($index == $lastIndex && $currentString) {
                if (in_array($currentString, $this->reservedWords)) {
                    $this->setRelativeLexeme($currentString, $index + 1);
                } else if (is_numeric($currentString)) {
                    $this->setConstant($currentString, $index + 1);
                } else {
                    var_dump($index, $currentString);
                    $this->setVariable($currentString, $index + 1);
                }
            }
        }

        return [
            'bool' => true,
            'message' => 'Success!',
        ];
    }
}