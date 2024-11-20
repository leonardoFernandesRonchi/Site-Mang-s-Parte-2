<style>
    /* Footer personalizado */
    .custom-footer {
        background-color: #d9534f; /* Vermelho escuro, combinando com a navbar */
        color: #fff;
        padding: 20px 0;
        text-align: center;
    }
    .custom-footer a {
        color: #fff;
        text-decoration: none;
    }
    .custom-footer a:hover {
        color: #ccc;
        text-decoration: underline;
    }
    .social-icons .glyphicon {
        margin: 0 10px;
        font-size: 20px;
    }
    /* Remove bordas da navbar */
    .footer {
        margin: 0; /* Remove margens indesejadas do footer */
    }
    @media (max-width: 576px) {
        .custom-footer .row {
            text-align: center; /* Centraliza o texto no footer em telas menores */
        }
    }
</style>

<footer class="footer custom-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <p>&copy; 2024 MeusManhwas</p>
            </div>
            <div class="col-xs-12 col-sm-4">
                <p><a href="#">Contato</a> | <a href="#">Sobre</a></p>
            </div>
            <div class="col-xs-12 col-sm-4 social-icons">
                <a href="#"><span class="glyphicon glyphicon-thumbs-up"></span></a>
                <a href="#"><span class="glyphicon glyphicon-heart"></span></a>
            </div>
        </div>
</footer>