<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($page_title) ? $page_title : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/new/template.css'); ?>">
    <style>
        .fira-sans-regular {
            font-family: "Fira Sans", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        function toggleDropdown(id) {
            var dropdown = document.getElementById(id);
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }
    </script>

</head>

<body class="fira-sans-regular">
    <div class="container-fluid no-print">
        <div class="row">
            <nav class="navbar navbar-expand-lg bg-light">
                <a class="navbar-brand me-5" href="#">
                    <span class="ms-1 d-flex">
                        <img src="<?= $entity_logo ?>" height=40>
                        <h5 class="ms-1 mt-1"><big><?= $entity[0] ?></big><span style="color:gray"> <?= $entity[1] ?></span></h5>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item p-2">
                            <a class="nav-link disabled" href="#" id="student" onclick="getLink('student')"><i class="bi bi-person-circle"></i> Student</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link disabled" href="#" id="teacher" onclick="getLink('teacher')"><i class="bi bi-file-person"></i> Teacher</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link disabled" href="#" id="fees" onclick="getLink('fees')"><i class="bi bi-currency-exchange"></i> Fees</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link disabled" href="#" id="report" onclick="getLink('report')"><i class="bi bi-file-earmark-bar-graph-fill"></i> Report</a>
                        </li>
                        <li class="nav-item p-2">
                            <a class="nav-link disabled" href="#" id="admin" onclick="getLink('admin')"><i class="bi bi-person-fill-gear"></i> Admin</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>