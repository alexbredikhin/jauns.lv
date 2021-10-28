<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- Styles -->


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .antialiased {
            display: flex;
            justify-content: center;
            text-align: center;
            width: 100%;
            height: 500px;
            flex-direction: column;
        }

        .start_game {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .questions {
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
            height: 500px;
        }
        .questions-answers {
            display: flex;
            width: 45%;
            justify-content: space-between;
        }
        .alert {
            display: none;
        }
        .spinner-border {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 100;
        }
    </style>
</head>
<body class="antialiased" style="display: flex;">
<div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
</div>
<div class="alert" role="alert">

</div>
<div class="start_game">
    Do you want to play?
    <button type="button" class="btn btn-primary start-game-button">Yes!</button>
</div>
<div class="questions">
    <div class="question-text"></div>
    <div class="questions-answers">
    </div>
</div>
</body>
<script>
    $(document).ready(function () {
        $(window).on('load', function() {
            cleanPreviousGame()
        });
        function cleanPreviousGame()
        {
            $.ajax({
                url: '/clean-game',
                type: 'POST',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
            });
        }
        $('.start-game-button').on('click', function () {
            $('.start_game').css('display', 'none');
            $('.questions').css('display', 'flex');
            getQuestion()
        })
        var questionCorrect = 0;
        $('body').on('click', '.btn-answer',function () {
            $('.spinner-border').css('display', 'block');
            $.ajax({
                url: '/answer',
                type: 'POST',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "answer": $(this).text(),
                },
                success: function (data) {
                    $('.spinner-border').css('display', 'none');
                    questionCorrect++;
                    if (questionCorrect === 20) {
                        questionCorrect = 0;
                        $('.alert').addClass('alert-success').css('display', 'block').text('You win!');
                        finishGame();

                        setTimeout(function () {
                            $('.alert').removeClass('alert-success').css('display', 'none').text('');
                        }, 2000)
                        return;
                    }
                    $('.alert').addClass('alert-success').css('display', 'block').text('Success');
                    setTimeout(function () {
                        $('.alert').removeClass('alert-success').css('display', 'none').text('');
                    }, 2000)
                    cleanQuestion();
                    getQuestion();
                },
                error: function (e, exception) {
                    $('.spinner-border').css('display', 'none');
                    if(e.status === 400) {
                        $('.alert').addClass('alert-danger').css('display', 'block').text(e.responseText);
                        finishGame();

                        setTimeout(function () {
                            $('.alert').removeClass('alert-danger').css('display', 'none').text('');
                        }, 2000)

                        return;
                    }

                    $('.alert').addClass('alert-danger').css('display', 'block').text('Sorry, something went wrong');
                    finishGame();
                    cleanPreviousGame();
                    setTimeout(function () {
                        $('.alert').removeClass('alert-danger').css('display', 'none').text('');
                    }, 2000)
                }
            });
        });
        function finishGame()
        {
            $('.questions').css('display', 'none');
            cleanQuestion();
            $('.start_game').css('display', 'flex');
        }
        function cleanQuestion()
        {
            $('.question-text').empty();
            $('.questions-answers').empty();
        }

        function getQuestion() {
            $('.spinner-border').css('display', 'block');
            $.ajax({
                url: '/question',
                type: 'POST',
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('.spinner-border').css('display', 'none');
                    $('.question-text').text(data.question)

                    let answers = data.answers;
                    $.each(answers, function(key, value) {
                        let buttons = $('<button  class="btn btn-info btn-answer" >' + value + '</button>')
                        buttons.appendTo('.questions-answers');
                    });
                },
                error: function (e, exception) {
                    $('.spinner-border').css('display', 'none');
                    $('.alert').addClass('alert-danger').css('display', 'block').text('Sorry, something went wrong');
                    finishGame();
                    cleanPreviousGame();
                    setTimeout(function () {
                        $('.alert').removeClass('alert-danger').css('display', 'none').text('');
                    }, 2000)
                }
            });
        }
    });
</script>
</html>
