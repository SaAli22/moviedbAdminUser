<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/d826f0fb4b.js" crossorigin="anonymous"></script>
</head>
<?php if (session()->has('admin')): ?>
<div id="admin-notification">
    You're logged in as an admin.
</div>
<?php endif; ?>
<body>
@include('search')
<div id="innerbody">
    <div id="title-div">
        <h1>Popular movies</h1>
        <?php if (session()->has('user')) {
            $temp = session('user');
            echo $temp->name;
        }
        ?>
    </div>
    <div id="searchPoster"></div>
    <div id="poster-div">
        <button id="left-arrow" style="visibility: hidden"><i class="fa-solid fa-chevron-left"></i></button>
        <?php
        $data = session('data');
        $poster = session('poster');
        if (isset($data)) {
            for ($i = 0; $i < 6; $i++) {
                echo '<a class="redposter"><img class="redposterimg poster" src="https://image.tmdb.org/t/p/w500' . $data[$i]->poster_path . '"></a>';
            }
        }
        ?>
        <button id="right-arrow"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    <h1 id="watchlist-title">Your Watchlist</h1>
    <div id="watchlist-div">

    </div>

</div>

<?php if (session()->has('admin')): ?>
<div id="admin-movies">
    <h2>Hidden Movies</h2>

    <div class="hidden-movie">


            <?php
            $data = session('data');
            $poster = session('poster');
            if (isset($data)) {
                for ($i = 6; $i < 12; $i++) {
                    echo '<a class="redposter"><img class="redposterimg poster" src="https://image.tmdb.org/t/p/w500' . $data[$i]->poster_path . '"></a>';
                }
            }
            ?>


    </div>

    <div id="admin-movies2">
        <h2>New movies</h2>
        <div class="hidden-movie1"></div>
        <!-- Form for adding new movie -->
        <form id="add-movie-form">
            <input type="text" id="title" placeholder="Title" required>
            <input type="file" id="poster_image" accept="image/*" required>
            <button type="button" onclick="addMovie()">Add Movie</button>
        </form>
    </div>

</div>
<?php endif; ?>
<script>

    function addMovie() {
        var title = document.getElementById('title').value;
        var imageFile = document.getElementById('poster_image').files[0];

        if (title.trim() === '') {
            alert('Please enter a title');
            return;
        }
        if (!imageFile) {
            alert('Please select an image');
            return;
        }

        var reader = new FileReader();
        reader.onload = function (event) {
            var imageUrl = event.target.result;

            var movieElement = document.createElement('div');
            movieElement.classList.add('redposter');
            movieElement.innerHTML = '<p>' + title + '</p><img class="redposterimg poster" src="' + imageUrl + '" alt="' + title + '">';


            var container = document.querySelector('.hidden-movie1');
            container.appendChild(movieElement);
        };
        reader.readAsDataURL(imageFile);
    }


    let counter = 0;
    let data = <?php echo json_encode($data); ?>;

    let posterdiv = document.querySelectorAll('.redposterimg');

    let rightarrow = document.querySelector('#right-arrow');
    let leftarrow = document.querySelector('#left-arrow');
    rightarrow.addEventListener('click', (event) => {
        counter++;
        posterdiv.forEach((element, i) => {
            element.setAttribute('src', 'https://image.tmdb.org/t/p/w500' + data[i + counter]
                .poster_path);
        })
        if (counter > 4) rightarrow.style.visibility = "hidden";
        else rightarrow.style.visibility = "visible";
        if (counter > 0) leftarrow.style.visibility = "visible";
        else leftarrow.style.visibility = "hidden";
    })

    leftarrow.addEventListener('click', (event) => {
        counter--;
        posterdiv.forEach((element, i) => {
            element.setAttribute('src', 'https://image.tmdb.org/t/p/w500' + data[i + counter]
                .poster_path);
        })
        if (counter > 0) leftarrow.style.visibility = "visible";
        else leftarrow.style.visibility = "hidden"
        if (counter > 4) rightarrow.style.visibility = "hidden"
        else rightarrow.style.visibility = "visible";
    })

    async function getPosterPath(movie_id) {
        return fetch(`/api/getPosterPath/${movie_id}`, {
            method: "GET"
        }).then(async (result) => {
            return result.json();
        })
    }

    async function getWatchlist(id) {
        return fetch(`/api/getUserWatchlist/${id}`, {
            method: "GET"
        }).then(async (result) => {
            return result.json();
        })
    }


    getWatchlist(
        @if (session()->has('user'))
            {{ session('user')->id }}
            @else
        - 1
        @endif
    ).then(async (response) => {
            let div = document.querySelector('#watchlist-div');
            if (response.length == 0) {
                let text = document.createElement('p');
                @if (session()->has('user'))
                    text.innerHTML = "Your watchlist is empty. Go to a movies page to add it to your watchlist";
                @else
                    text.innerHTML = "Login to access your watchlist"
                @endif

                text.setAttribute('id', 'emptywatchlist')
                div.appendChild(text);
            }
            for (let i = 0; i < 6; i++) {
                let movie = document.createElement('img');
                let a = document.createElement('a')
                a.setAttribute('class', 'redposter')
                if (i < response.length) {
                    let posterpath = await getPosterPath(response[i].movie_id);
                    movie.setAttribute('src', `https://image.tmdb.org/t/p/w500${posterpath.poster_path}`)
                    a.setAttribute('href', `/movie/${response[i].movie_id}`)
                } else {
                    movie.style.visibility = 'hidden';
                }

                movie.setAttribute('class', 'poster')
                a.appendChild(movie)
                div.appendChild(a)
            }
        })

    document.querySelector("#form").addEventListener("submit", (event) => {
        event.preventDefault();
        const input = document.querySelector("#input").value;
        $.ajax({
            url: 'api/test/' + input,
            type: "GET",
            success: (result) => {
                document.querySelector('#searchPoster').innerHTML +=
                    `<img class="poster" src="https://image.tmdb.org/t/p/w500${result}">`
            }
        })
    });

    var posters = document.querySelectorAll(".redposter");

    posters.forEach((element, i) => {
        element.addEventListener('click', (event) => {
            window.location.href = '/movie/' + data[i + counter].id;
        })
    })
</script>
</body>

</html>
<style>
    #image {
        display: flex;
    }

    body {
        background-color: #000;
        color: white;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        height: 100%;
    }

    h1 {
        font-size: 26px;
    }

    #innerbody {
        background-color: #111;
        margin: auto;
        min-height: inherit;
        height: inherit;
        padding: 0 2rem 10rem 2rem;
        max-width: 75%;
        display: flex;
        flex-direction: column;
    }

    .poster {
        width: 150px;
        height: 225px;
        padding: 1vh;
        margin: 0 1rem 0 1rem;
    }

    #admin-movies {
        background-color: #111;
        margin: auto;
        min-height: inherit;
        height: inherit;
        padding: 0 2rem 10rem 2rem;
        max-width: 75%;
        display: flex;
        flex-direction: column;

    }

    .redposter {
        background-color: #222;
    }

    #poster-div,
    #watchlist-div {
        display: flex;
        place-content: center;
    }

    #title-div,
    #watchlist-title {
        margin-top: 2rem;
        margin-left: 9rem;
    }

    #right-arrow {
        display: flex;
        place-self: center;
    }


    #left-arrow {
        display: flex;
        place-self: center;
    }

    #left-arrow,
    #right-arrow {
        border: none;
        background: none;
    }


    .fa-solid {
        color: white;
    }

    #right-arrow:hover,
    #left-arrow:hover {
        opacity: 0.8;
    }

    #emptywatchlist {
        position: absolute;
        margin-top: 7rem;
    }
</style>
