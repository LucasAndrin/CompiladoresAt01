<!doctype html>
<html lang="en">
<?php
require_once 'Automaton.php';
$string = $_GET['text'] ?? '';
$string = trim($string);
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Automato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<style>
    .bg-indigo {
        background-color: var(--bs-indigo);
    }

    .text-indigo {
        color: var(--bs-indigo) !important;
    }
</style>
<body>
    <div class="container-fluid p-5">
        <div class="col-md-10 shadow p-3 mb-5 mx-auto card">
            <form action="" method="get" class="mb-3">
                <h4>Autômoto Final Determinístico</h4>
                <div class="form-floating form-floating-sm mb-3">
                    <textarea name="text" id="floatingInput" class="form-control" placeholder="Entre com o seu texto aqui!" style="height: 100px"><?php echo $string ?></textarea>
                    <label for="floatingInput">Entre com o seu texto aqui!</label>
                    <div class="form-text">
                        <ul>
                            <li>IDs - nome de variáveis, devem começar com ao menos uma letra, que pode ser seguida de letras ou números</li>
                            <li>Constantes - valores numéricos</li>
                            <li>as palavras reservadas: IF, FOR e WHILE</li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <?php
                if ($string) {
                    $automaton = new Automaton($string);
                    $result = $automaton->test();
                    if (!$result['bool']) {
                        ?><span class="badge text-bg-danger"><?php echo $result['message'] ?></span><?php
                    }
            ?>
                <code class="rounded bg-dark text-white p-5">
                    <h4><code class="text-danger bg-dark rounded p-1">constants</code></h4>
                    <pre><?php print_r($automaton->getConstant()) ?></pre>
                    <br>

                    <h4><code class="text-warning bg-dark rounded p-1">variables</code></h4>
                    <pre><?php print_r($automaton->getVariable()) ?></pre>
                    <br>

                    <?php foreach ($automaton->reservedWords as $reservedWord) { ?>
                        <h4><code class="text-indigo bg-dark rounded p-1"><?php echo $reservedWord ?></code></h4>
                        <pre><?php print_r($automaton->{$reservedWord}) ?></pre>
                        <br>
                    <?php } ?>
                </code>
            <?php
                }
            ?>
        </div>
    </div>
</body>
</html>