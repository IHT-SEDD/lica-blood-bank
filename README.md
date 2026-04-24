<!-- ==================== Link untuk balik ke bagian paling atas README ==================== -->

<a id="readme-top"></a>

<!-- ====================
Project Shield untuk badge di README
Referensi : https://www.markdownguide.org/basic-syntax/#reference-style-links
==================== -->

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]

<br />

<!-- ==================== Bagian Header :begin ==================== -->
<div align="center">
 <!-- ==================== Project Logo ==================== -->
 <a href="https://github.com/IHT-SEDD/lica-blood-bank">
   <img src="public/assets/images/logos/logo-lica-bb.png" alt="Logo" width="80" height="80">
 </a>

 <!-- ==================== Project Name ==================== -->
 <h3 align="center">LICA Blood Bank</h3>
</div>
<!-- ==================== Bagian Header :end ==================== -->

<!-- ==================== Konten :begin ==================== -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>
<!-- ==================== Konten :end ==================== -->

<!-- ==================== Tentang Project :begin ==================== -->

## About The Project

LICA Blood Bank is a system designed to support hospital or BDRS blood bank operations.

It manages blood inventory, handles blood requests, and supports donor processes including registration, scheduling, and screening. The system improves efficiency, accuracy, and coordination in blood transfusion services.

<p align="right">(<a href="#readme-top">Back to top</a>)</p>

<!-- ==================== Tentang Project :end ==================== -->

<!-- ==================== Project dibuat dengan apa :begin ==================== -->

### Built With

- [![Laravel][Laravel.com]][Laravel-url]
- [![Bootstrap][Bootstrap.com]][Bootstrap-url]
- [![JavaScript][JavaScript.com]][JavaScript-url]

<p align="right">(<a href="#readme-top">Back to top</a>)</p>

<!-- ==================== Project dibuat dengan apa :end ==================== -->

<!-- ==================== Cara instalasi :begin ==================== -->

## Getting Started

Follow the instructions below to install and setup this project on your local computer.

<!-- ==================== Pre-requisites ==================== -->

### Prerequisites

List below is a pre requisites you should have to run this project locally.

- Latest Version of NPM (Node Package Manager)
    ```sh
    npm install npm@latest -g
    ```
- PHP 8.4.12 (Copy link below to download)
    ```sh
    https://downloads.php.net/~windows/releases/archives/php-8.4.12-nts-Win32-vs17-x64.zip
    ```
- MySQL 9.4.0 (Copy link below to download)
    ```sh
    https://downloads.mysql.com/archives/get/p/23/file/mysql-9.4.0-winx64.msi
    ```

<!-- ==================== Installations ==================== -->

### Installation

_Follow instructions below to install this project._

1. Clone the repo
    ```sh
    git clone https://github.com/IHT-SEDD/lica-blood-bank.git
    ```
2. Install the vendor library
    ```sh
    composer install
    ```
3. Discover and update the autoload
    ```sh
    composer dump-autoload
    ```
4. Install NPM packages
    ```sh
    npm install
    ```
5. Get .env files
    ```sh
    cp .env.example .env
    ```
6. Generate application key
    ```sh
    php artisan key:generate
    ```
7. Setup database in .env & run migration
    ```sh
    php artisan migrate
    php artisan db:seed
    ```

<p align="right">(<a href="#readme-top">Back to top</a>)</p>

<!-- ==================== Cara instalasi :end ==================== -->

<!-- CONTRIBUTING -->

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Top contributors:

<a href="https://github.com/IHT-SEDD/lica-blood-bank/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=IHT-SEDD/lica-blood-bank" alt="contrib.rocks image" />
</a>

<p align="right">(<a href="#readme-top">Back to top</a>)</p>

<!-- LICENSE -->

## License

Distributed under the Unlicense License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#readme-top">Back to top</a>)</p>

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/IHT-SEDD/lica-blood-bank.svg?style=for-the-badge
[contributors-url]: https://github.com/IHT-SEDD/lica-blood-bank/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/IHT-SEDD/lica-blood-bank.svg?style=for-the-badge
[forks-url]: https://github.com/IHT-SEDD/lica-blood-bank/network/members
[stars-shield]: https://img.shields.io/github/stars/IHT-SEDD/lica-blood-bank.svg?style=for-the-badge
[stars-url]: https://github.com/IHT-SEDD/lica-blood-bank/stargazers
[issues-shield]: https://img.shields.io/github/issues/IHT-SEDD/lica-blood-bank.svg?style=for-the-badge
[issues-url]: https://github.com/IHT-SEDD/lica-blood-bank/issues
[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com
[JavaScript.com]: https://img.shields.io/badge/javascript-FCDC00?style=for-the-badge&logo=javascript&logoColor=black
[JavaScript-url]: https://www.javascript.com/
