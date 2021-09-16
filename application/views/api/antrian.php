<div class="container mt-5">
    <!-- SOUND LOKET -->
    <!-- Kondisi I - 5 text -->
    <audio id="intro">
        <source src="<?php base_url(); ?>assets/audio/in.wav" type="audio/ogg">
    </audio>
    <audio id="nomor_antrian">
        <source src="<?php base_url(); ?>assets/audio/antrian.wav" type="audio/ogg">
    </audio>
    <audio id="sebut_angka">
        <source src="<?php base_url(); ?>assets/audio/sepuluh.mp3" type="audio/ogg">
    </audio>
    <audio id="ke_loket">
        <source src="<?php base_url(); ?>assets/audio/loket.mp3" type="audio/ogg">
    </audio>
    <audio id="loket">
        <source src="<?php base_url(); ?>assets/audio/4.mp3" type="audio/ogg">
    </audio>

    <!-- Kondisi 2 - 7 text / dua puluh sembilan -->
    <audio id="dua">
        <source src="<?php base_url(); ?>assets/audio/9.mp3" type="audio/ogg">
    </audio>
    <audio id="puluh">
        <source src="<?php base_url(); ?>assets/audio/puluh.mp3" type="audio/ogg">
    </audio>
    <audio id="satu">
        <source src="<?php base_url(); ?>assets/audio/9.mp3" type="audio/ogg">
    </audio>

    <!-- Kondisi 3 - 6 text / belasan -->
    <audio id="sembilan">
        <source src="<?php base_url(); ?>assets/audio/9.mp3" type="audio/ogg">
    </audio>
    <audio id="belas">
        <source src="<?php base_url(); ?>assets/audio/belas.mp3" type="audio/ogg">
    </audio>

    <!-- HURUF -->
    <audio id="huruf_a">
        <source src="<?php base_url(); ?>assets/audio/a.wav" type="audio/ogg">
    </audio>
    <audio id="huruf_d">
        <source src="<?php base_url(); ?>assets/audio/d.wav" type="audio/ogg">
    </audio>
    <audio id="huruf_l">
        <source src="<?php base_url(); ?>assets/audio/l.wav" type="audio/ogg">
    </audio>
    <!-- END SOUND LOKET -->

    <!-- Kondisi I -->
    <input type="text" id="text_1">
    <input type="text" id="text_2">
    <input type="text" id="text_3">
    <input type="text" id="text_4">
    <input type="text" id="text_5">
    <hr>

    <!-- Kondisi II -->
    <input type="text" id="text_6" value="1">
    <input type="text" id="text_7" value="1">
    <input type="text" id="text_8" value="1">
    <input type="text" id="text_9" value="1">
    <input type="text" id="text_10" value="1">
    <input type="text" id="text_11" value="0">
    <input type="text" id="text_12" value="1">
    <hr>

    <!-- Kondisi III -->
    <input type="text" id="text_13" value="1">
    <input type="text" id="text_14" value="1">
    <input type="text" id="text_15" value="1">
    <input type="text" id="text_16" value="1">
    <input type="text" id="text_17" value="0">
    <input type="text" id="text_18" value="1">
    <hr>

    <!-- Huruf -->
    <input type="text" id="text_19" value="A">
    
    <hr>
    <p>Klik tombol panggil</p>
    <br>

    <button class="btn btn-success" type="button" id="button_click">Panggil</button>
    <button class="btn btn-info" type="button_close" type="button">Stop</button>
</div>

<script>
    $(document).ready(function() {
        $('#button_click').on('click', function() {
            var intro = document.getElementById("intro");
            var nomor_antrian = document.getElementById("nomor_antrian");
            var ke_loket = document.getElementById("ke_loket");
            var loket = document.getElementById("loket");

            // Kondisi 1
            var sebut_angka = document.getElementById("sebut_angka");

            // Kondisi 2
            var dua = document.getElementById("dua");
            var puluh = document.getElementById("puluh");
            var satu = document.getElementById("satu");

            // Kondisi 3
            var sembilan = document.getElementById("sembilan");
            var belas = document.getElementById("belas");

            // Panggil Huruf
            var huruf_a = document.getElementById("huruf_a");
            var huruf_d = document.getElementById("huruf_d");
            var huruf_l = document.getElementById("huruf_l");

            var text_1 = $('#text_1').val();
            var text_2 = $('#text_2').val();
            var text_3 = $('#text_3').val();
            var text_4 = $('#text_4').val();
            var text_5 = $('#text_5').val();
            var text_6 = $('#text_6').val();
            var text_7 = $('#text_7').val();
            var text_8 = $('#text_8').val();
            var text_9 = $('#text_9').val();
            var text_10 = $('#text_10').val();
            var text_11 = $('#text_11').val();
            var text_12 = $('#text_12').val();
            var text_13 = $('#text_13').val();
            var text_14 = $('#text_14').val();
            var text_15 = $('#text_15').val();
            var text_16 = $('#text_16').val();
            var text_17 = $('#text_17').val();
            var text_18 = $('#text_18').val();
            var text_19 = $('#text_19').val();



            if (text_1 == "1" && text_2 == "1" && text_3 == "1" && text_4 == "1" && text_5 == "1") {
                setTimeout(function playAudio() {
                    intro.play();
                })
                setTimeout(function playAudio() {
                    nomor_antrian.play();
                }, 3000)

                if (text_19 == "A") {
                    setTimeout(function playAudio() {
                        huruf_a.play();
                    }, 5000)
                } else if (text_19 == "D") {
                    setTimeout(function playAudio() {
                        huruf_d.play();
                    }, 5000)
                } else {
                    setTimeout(function playAudio() {
                        huruf_l.play();
                    }, 5000)
                }

                setTimeout(function playAudio() {
                    sebut_angka.play();
                }, 6000)
                setTimeout(function playAudio() {
                    ke_loket.play();
                }, 7000)
                setTimeout(function playAudio() {
                    loket.play();
                }, 8000)
            }

            if (text_6 == 1 && text_7 == 1 && text_8 == 1 && text_9 == 1 && text_10 == 1 && text_11 == 1 && text_12 == 1) {
                setTimeout(function playAudio() {
                    intro.play();
                })
                setTimeout(function playAudio() {
                    nomor_antrian.play();
                }, 3000)

                if (text_19 == "A") {
                    setTimeout(function playAudio() {
                        huruf_a.play();
                    }, 5000)
                } else if (text_19 == "D") {
                    setTimeout(function playAudio() {
                        huruf_d.play();
                    }, 5000)
                } else {
                    setTimeout(function playAudio() {
                        huruf_l.play();
                    }, 5000)
                }

                setTimeout(function playAudio() {
                    dua.play();
                }, 6000)
                setTimeout(function playAudio() {
                    puluh.play();
                }, 7000)
                setTimeout(function playAudio() {
                    satu.play();
                }, 8000)

                setTimeout(function playAudio() {
                    ke_loket.play();
                }, 9000)
                setTimeout(function playAudio() {
                    loket.play();
                }, 10000)
            }

            if (text_13 == 1 && text_14 == 1 && text_15 == 1 && text_16 == 1 && text_17 == 1 && text_18 == 1) {
                setTimeout(function playAudio() {
                    intro.play();
                })
                setTimeout(function playAudio() {
                    nomor_antrian.play();
                }, 3000)

                if (text_19 == "A") {
                    setTimeout(function playAudio() {
                        huruf_a.play();
                    }, 5000)
                } else if (text_19 == "D") {
                    setTimeout(function playAudio() {
                        huruf_d.play();
                    }, 5000)
                } else {
                    setTimeout(function playAudio() {
                        huruf_l.play();
                    }, 5000)
                }

                setTimeout(function playAudio() {
                    sembilan.play();
                }, 6000)
                setTimeout(function playAudio() {
                    belas.play();
                }, 7000)

                setTimeout(function playAudio() {
                    ke_loket.play();
                }, 8000)
                setTimeout(function playAudio() {
                    loket.play();
                }, 9000)
            }
        })

        $('#button_close').on('click', function() {
            function pauseAudio() {
                x.pause();
            }
        })
    });
</script>