-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jul 2019 pada 19.59
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kampus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_list`
--

CREATE TABLE `chat_list` (
  `room_chat_id` varchar(100) NOT NULL,
  `chat_title` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_chat_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(100) NOT NULL,
  `room_type` enum('Private','Group') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_member`
--

CREATE TABLE `chat_member` (
  `chat_room_member_id` varchar(100) NOT NULL,
  `room_chat_id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `user_type` enum('Admin','Member') NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_message`
--

CREATE TABLE `chat_message` (
  `message_id` varchar(100) NOT NULL,
  `room_chat_id` varchar(100) NOT NULL,
  `send_by` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `send_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_readed`
--

CREATE TABLE `chat_readed` (
  `chat_readed_id` varchar(100) NOT NULL,
  `room_chat_id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `readed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` varchar(100) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `nama`, `username`, `password`) VALUES
('12bf563ec7fb48e88bbe9d664bee40c7', 'Alexander Pierce', 'alex', 'lwnAs8dWgnPqxWMGQWJ0IKWgao/yNHS/p2B1e7DCGESLWO6CrE+YGUhjs75iddOpc6VvwwclpP/oqHeMm7Cn/w=='),
('25389c893123400ca86fd401e001eaea', 'Maria Gonzales', 'maria', 'L/GB3/u0flb0LgYGn2IKRjdOfgqvHEW4kt45Ah7qRgeKZN/xaLlhOSu2a0VQo0hIfuc67TgVTHJL1p4WujWiQg=='),
('272c071824b14722bf97c397c10cc043', 'Bayu Hidayah M.', 'bayu123', 'LkuzoGiizk5b1vPSyM8eVpRH7NhgoMhChSKFMUzvqqLK02BoNIzLrZuYs0Sc3kaz8MHLem0h3sprC0rkv/ikAA=='),
('3f2868c4689745ae80e4a063bf5bc360', 'Jonathan Burke Jr.', 'jonathan', 'rI8KanTuzBoNi9wjwYWUTtdRMkX7APIVUMuopWtIc3+QBAuhVMnBGJfzt2HpVTul/YAYRJVh4TTuHTKdjpQuIQ=='),
('430849cd7acd48b0aa902503b4a03cc7', 'Luna Stark', 'luna', 'Hu9j/MK3zRESkzuEVBJneNK40ZmIbggoiqyr77U1rR1jPM8PKCA4L0cGU5eqiI8/0eEaCx8OmFZ8Z5A8q81xWA=='),
('b0b33e36778f4420866950945425b774', 'Eric Horn', 'eric', 'LsOPnBrymJXnSKE31xeDy/UxVnhIMiWG8sh/eEMJFIErVPxEnlgCE91Wd7qncAL7W5KtKTs0+iNrvzctt3L0ng=='),
('be8e01e3de5645b085043e7f9b1ca532', 'John Doe', 'john', 'PCqLwnwDP5SCmLcmfE+NMiJTCZZN5YE7SNf4wML4W/2F6LvaoOv6BosXaH4QBjHdEnJHXLOzswaZFvlxdiauFw=='),
('e4173753439e482bba17c3cd432a9b8a', 'Sarah Bullock', 'sarah', 'SwwKkImdSw4BAbDsufMBeuq4WFD9zjCN22po7gjmlvUIFXpqS+hSsqQfP2bHg999rPWSBG2g68Q4HVV5LveQVw=='),
('e9eeee60838f40e2bcb8c41101dd5b4c', 'Nadia Carmichael', 'nadia', 'X33UftbVeRxmjOK3mOdtuZ6nlsfVnQmWu4afrgeehbN5Q3yDi/97VSneCo/l7jTJUBnXtWUpqJ4iUETe+FzP4w==');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `chat_list`
--
ALTER TABLE `chat_list`
  ADD PRIMARY KEY (`room_chat_id`);

--
-- Indeks untuk tabel `chat_member`
--
ALTER TABLE `chat_member`
  ADD PRIMARY KEY (`chat_room_member_id`);

--
-- Indeks untuk tabel `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indeks untuk tabel `chat_readed`
--
ALTER TABLE `chat_readed`
  ADD PRIMARY KEY (`chat_readed_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
