<!DOCTYPE html>
<html lang="hy">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Հարսանիք</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Армянский шрифт -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;700&display=swap" rel="stylesheet">

<style>
body, html {
    margin: 0;
    padding: 0;
    font-family: 'Noto Sans Armenian', sans-serif;
    scroll-behavior: smooth;
} 


/* Общий фон для всей страницы */
.hero-s {
    background-image: url('/images/fon.jpg');
    background-repeat: no-repeat;
    background-position: center top;
    background-size: cover;   /* растягиваем фон на весь экран */
    background-attachment: fixed; /* фон остаётся на месте при скролле */
    color: white;
    position: relative;
    padding-top: 160px;
    padding-bottom: 60px;    /* чтобы контент не прилипал к низу */
}


/* Полупрозрачный overlay для читаемости текста */
.hero-s::before {
    content: "";
    position: absolute;
    top:0; left:0;
    width:100%; height:100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 0;
}

/* Чтобы все элементы были поверх overlay */
.section, .hero {
    position: relative;
    z-index: 1;
}



.section img {
    width: 100%;
    height: auto;      /* показываем полностью без обрезки */
    border-radius: 15px;
    margin-bottom: 20px;
    filter: brightness(0.7);
}

.section h2, .hero h1 {
    margin-bottom: 10px;
}

.btn-map {
    margin-top: 10px;
    background-color: #ffffff;
    border: solid 1px white;
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 700;
    transition: 0.3s;
    color: white;
    background-color: transparent;
}
/* 
.btn-map:hover {
    background-color: #f0f0f0;
    color: #000;
} */

.timer {
    display: flex;
    justify-content: center;
    gap: 10px; /* расстояние между блоками */
    margin-top: 20px;
    flex-wrap: wrap;
}

.timer .time-box {
    background-color: transparent; /* полупрозрачный фон */
    padding: 15px 10px;
    border-radius: 10px;
    min-width: 70px;
    text-align: center;
}

.timer .time-box span {
    display: block;
    font-size: 1.5rem; /* цифры */
    font-weight: 700;
}

.timer .time-box small {
    display: block;
    font-size: 0.8rem; /* подписи */
    margin-top: 5px;
    color: #fff;
}
.start-content{
    padding-top:160px;
}
.wedding-date {
    text-align: center;
    margin: 30px 0;
}

.date-box {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 15px; /* расстояние между блоками */
    padding: 15px 30px;
    background-color: transparent; /* полупрозрачный фон */
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    font-family: 'Noto Sans Armenian', sans-serif;
}

.date-box span {
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
}

.date-box .separator {
    font-size: 1.5rem;
    color: #ffe5b4; /* светло-бежевый/кремовый цвет для разделителей */
}

/* Адаптивность */
@media(max-width: 768px) {
    .hero {
        padding: 40px 20px;
    }
    .timer {
        font-size: 1.5rem;
    }
    .section h2, .hero h1 {
        font-size: 1.5rem;
    }
}
</style>

</head>
<body>
<div class="hero-s">
    <div class="container text-center start-content">
        <div class="row">
            <div class="col-12">
                <img src="/images/anun.png" style="max-width:300px;" alt="">
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <h3>Հարսանիքին մնաց՝</h3>
                <div id="countdown" class="timer"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <p>
                    Սիրով հրավիրում ենք Ձեզ կիսելու մեզ հետ մեր կյանքի կարևոր և հիշարժան օրը
                </p>
                <div class="wedding-date">
                    <div class="date-box">
                        <span class="day">16</span>
                        <span class="separator">|</span>
                        <span class="month">11</span>
                        <span class="separator">|</span>
                        <span class="year">2025</span>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
    <div class="container text-center pt-5">
        <div class="row">
            <div class="col-12">
                <img src="/images/mat.png" style="max-width:300px;" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Պսակադրության արարողություն</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p><strong>14:00</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p><strong>Հաղարծին Վանական Համալիր</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="https://www.google.com/maps" target="_blank" class="btn btn-map">Ինչպես հասնել</a>
            </div>
        </div>
    </div>
    <div class="container text-center pt-5 pb-5 mb-5">
        <div class="row">
            <div class="col-12">
                <img src="/images/res.png" style="max-width:300px;" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Հարսանյաց Հանդիսություն</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p><strong>17:00</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p><strong>Գրանադա Հոլլ</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a href="https://www.google.com/maps" target="_blank" class="btn btn-map">Ինչպես հասնել</a>
            </div>
        </div>
    </div>
</div>
</body>

<script>
const weddingDate = new Date("2025-12-25T14:00:00").getTime();
const countdownElement = document.getElementById('countdown');

function updateCountdown() {
    const now = new Date().getTime();
    const distance = weddingDate - now;

    if(distance < 0) {
        countdownElement.innerHTML = "<div>Հարսանիքը արդեն կայացել է!</div>";
        clearInterval(timer);
        return;
    }

    const days = Math.floor(distance / (1000*60*60*24));
    const hours = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
    const minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
    const seconds = Math.floor((distance % (1000*60)) / 1000);

    countdownElement.innerHTML = `
        <div class="time-box"><span>${days}</span><small>Օր</small></div>
        <div class="time-box"><span>${hours}</span><small>Ժամ</small></div>
        <div class="time-box"><span>${minutes}</span><small>Րոպե</small></div>
        <div class="time-box"><span>${seconds}</span><small>Վայրկյան</small></div>
    `;
}

updateCountdown();
const timer = setInterval(updateCountdown, 1000);
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
