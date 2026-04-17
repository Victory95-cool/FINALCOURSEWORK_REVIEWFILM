-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 11:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finalcoursework`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`) VALUES
(1, 'Victor', '$2y$10$L29sra8o03Uqd.GwrNH63O.kfCOqrfcjEb11V.IIvBAJDdXMOveOq', 'vinhthang0905@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `subject`, `created_at`) VALUES
(13, 'Make an Account', '2026-04-08 18:13:16'),
(19, 'Make an Account', '2026-04-08 18:52:32'),
(21, 'Make an Account', '2026-04-12 14:05:49'),
(26, 'Add Film', '2026-04-14 07:28:48'),
(27, 'NEW MOVIE 🐦‍🔥🐦‍🔥', '2026-04-14 10:05:00'),
(28, 'Add Film', '2026-04-14 18:55:20'),
(29, 'A Request for a Movie', '2026-04-15 19:39:22'),
(30, 'NEW MOVIE 🐦‍🔥🐦‍🔥', '2026-04-15 19:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE `directors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `directors`
--

INSERT INTO `directors` (`id`, `name`) VALUES
(2, 'Anthony Russo, Joe Russo'),
(4, 'Brett Ratner'),
(3, 'Christopher Nolan'),
(9, 'Gavin O\'Connor'),
(11, 'George Tillman Jr.'),
(1, 'James Cameron'),
(8, 'Marc Webb'),
(10, 'Martin Scorsese'),
(6, 'Peter Jackson'),
(5, 'Sylvester Stallone'),
(7, 'Todd Phillips');

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `released_year` year(4) NOT NULL DEFAULT 2012,
  `created_at` datetime DEFAULT current_timestamp(),
  `genre_id` int(11) NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `director_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `title`, `released_year`, `created_at`, `genre_id`, `poster`, `description`, `director_id`) VALUES
(1, 'Inception', '2010', '2026-03-06 17:25:45', 3, 'images/Inception.jpg', 'Dom Cobb is a skilled thief and the absolute best in the dangerous art of extraction: stealing valuable secrets from deep within the subconscious during the dream state. Cobb rare ability has made him a coveted player in the treacherous world of corporate espionage, but it has also made him an international fugitive. Now offered a chance at redemption, Cobb and his team must pull off the impossible: inception. Instead of stealing an idea, they must plant one into the mind of a corporate heir, all while Cobb battles the manifestations of his own tragic past that threaten the mission.', 3),
(2, 'Interstellar', '2014', '2026-03-06 17:25:45', 3, 'images/Interstellar.jpg', 'In a future where Earth is being ravaged by blight and dust storms, humanity is on the brink of extinction. Cooper, a former NASA pilot turned farmer, is recruited for a secret mission to find a new home for the human race among the stars. Leaving behind his family, Cooper leads a team of scientists through a newly discovered wormhole near Saturn. As they explore alien worlds where time moves differently due to gravitational time dilation, Cooper must balance the weight of humanity survival against the deep longing to return to his daughter, Murph.', 3),
(3, 'The Dark Knight', '2008', '2026-03-06 17:25:45', 2, 'images/The_Dark_Knight.jpg', 'Batman raises the stakes in his war on crime in Gotham City, working alongside Lieutenant Jim Gordon and District Attorney Harvey Dent to dismantle the remaining criminal organizations. The partnership proves effective until they find themselves prey to a reign of chaos unleashed by a rising criminal mastermind known as The Joker. This theatrical villain seeks to prove that anyone can be corrupted and that order is a fragile illusion, forcing Batman to confront the thin line between heroism and vigilantism as the city descends into anarchy.', 3),
(4, 'Avatar', '2009', '2026-03-06 17:25:45', 3, 'images/Avatar.jpg', 'Jake Sully is a paraplegic former Marine who takes his late twin brother place on a mission to the distant moon of Pandora. There, he infiltrates the indigenous Navi people using a biological vessel called an \"Avatar\". As he falls in love with the chieftain daughter, Neytiri, and learns the sacred connection between the Navi and their environment, Jake finds himself at the center of an escalating conflict. He must eventually choose between the militaristic RDA corporation, which seeks to mine the moons precious \"Unobtanium,\" and the Navi people who fight for their survival and the preservation of their home, Eywa.', 1),
(5, 'Titanic', '1997', '2026-03-06 17:25:45', 4, 'images/Titanic.jpg', 'Rose DeWitt Bukater is a 17-year-old high-society girl who feels trapped by the stifling expectations of her class and an impending forced marriage. On the maiden voyage of the R.M.S. Titanic, she meets Jack Dawson, a free-spirited, working-class artist. Despite their drastically different backgrounds, a passionate romance blossoms between them. Their forbidden love story unfolds against the backdrop of the \"unsinkable\" ship tragic encounter with a massive iceberg, turning a journey of luxury into a desperate struggle for survival.', 1),
(6, 'Rush Hour', '1998', '2026-03-13 14:31:08', 1, 'images/Rush_Hour.jpg', 'When the daughter of a Chinese diplomat is kidnapped in Los Angeles, Hong Kong top police inspector, Lee, is sent to help the investigation. The FBI, wanting no outside interference, assigns a loud-mouthed, rebellious LAPD detective named James Carter to \"babysit\" Lee and keep him away from the case. The two couldnt be more different, but after a series of hilarious misunderstandings and high-octane action, they realize they must work together to outsmart the kidnappers and uncover a vast criminal conspiracy.', 4),
(13, 'Avengers: Endgame', '2019', '2026-04-04 20:26:09', 2, 'images/Avengers_Endgame.jpg', 'Picking up after the cataclysmic events of Avengers: Infinity War, the universe is in ruins as half of all life has been erased by the Mad Titan, Thanos. The surviving heroes remain scattered and defeated, grieving the loss of their friends and families. However, a glimmer of hope arises when Scott Lang returns from the Quantum Realm, suggesting a desperate plan to travel back through time and retrieve the Infinity Stones. The Avengers must reunite, overcome their internal conflicts, and mount one final, time-spanning heist that leads to an earth-shattering final battle to restore balance to the universe.', 2),
(14, 'Rocky IV', '1985', '2026-04-10 15:34:15', 7, 'images/Rocky_IV.jpg', 'Rocky Balboa faces a devastating personal loss when his friend Apollo Creed is killed in the ring by Ivan Drago, a cold and powerhouse boxer from the Soviet Union. Driven by grief and patriotism, Rocky agrees to a non-sanctioned fight against Drago on Christmas Day in Russia. While Drago trains with state-of-the-art technology, Rocky retreats to the snowy Siberian wilderness to train using primitive methods. The match becomes a symbolic battle of the Cold War, testing Rocky physical limits and indomitable spirit.', 5),
(20, 'The Hobbit: The Battle of the Five Armies', '2014', '2026-04-10 15:57:51', 6, 'images/The_Hobbit.jpg', 'Having reclaimed their homeland from the Dragon Smaug, the Company of Dwarves has unwittingly unleashed a deadly force. As Smaug rains fire down upon Lake-town, Thorin Oakenshield becomes obsessed with his reclaimed treasure, sacrificing friendship and honor to hoard it. Meanwhile, the Wizard Gandalf discovers that the great enemy Sauron has sent legions of Orcs to attack the Lonely Mountain. The races of Dwarves, Elves, and Men must decide whether to unite or be destroyed in an epic battle for the fate of Middle-earth.', 6),
(25, 'The Joker', '2019', '2026-04-11 19:04:23', 5, 'images/Joker.jpg', 'Arthur Fleck, a party clown and a failed stand-up comedian, leads an impoverished life with his ailing mother. However, when society shuns him and brands him as a freak, he decides to embrace the life of chaos in Gotham City.', 7),
(26, 'The Amazing Spiderman', '2012', '2026-04-11 19:08:05', 2, 'images/The_Amazing_Spiderman.jpg', 'After Peter Parker is bitten by a genetically altered spider, he gains newfound, spider-like powers and ventures out to save the city from the machinations of a mysterious reptilian foe.', 8),
(27, 'Warrior', '2011', '2026-04-14 14:20:30', 7, 'images/Warrior.jpg', 'The youngest son of an alcoholic former boxer returns home, where he\'s trained by his father for competition in a mixed martial arts tournament - a path that puts the fighter on a collision course with his estranged, older brother.', 9),
(30, 'Big George Foreman', '2026', '2026-04-16 03:29:26', 7, 'images/Big_George_Foreman.jpg', 'Fueled by an impoverished childhood, George Foreman channeled his anger into becoming an Olympic Gold medalist and World Heavyweight Champion, followed by a near-death experience that took him from the boxing ring to the pulpit.', 11);

-- --------------------------------------------------------

--
-- Table structure for table `film_stars`
--

CREATE TABLE `film_stars` (
  `film_id` int(11) NOT NULL,
  `star_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film_stars`
--

INSERT INTO `film_stars` (`film_id`, `star_id`) VALUES
(1, 11),
(1, 12),
(1, 13),
(1, 31),
(1, 32),
(1, 33),
(2, 14),
(2, 15),
(2, 16),
(2, 34),
(2, 35),
(3, 17),
(3, 18),
(3, 19),
(3, 36),
(3, 37),
(3, 38),
(4, 5),
(4, 6),
(4, 7),
(4, 26),
(4, 27),
(5, 11),
(5, 20),
(5, 21),
(5, 39),
(5, 40),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(13, 8),
(13, 9),
(13, 10),
(13, 28),
(13, 29),
(13, 30),
(14, 22),
(14, 23),
(14, 41),
(14, 42),
(14, 43),
(20, 24),
(20, 25),
(20, 44),
(20, 45),
(20, 46),
(20, 48),
(20, 50),
(25, 53),
(25, 54),
(25, 55),
(25, 56),
(25, 57),
(25, 58),
(26, 59),
(26, 60),
(27, 31),
(27, 61),
(27, 62),
(30, 67),
(30, 68),
(30, 69),
(30, 70),
(30, 71);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'Comedy'),
(2, 'Action'),
(3, 'Science Fiction'),
(4, 'Romance'),
(5, 'Crime'),
(6, 'Fantasy'),
(7, 'Sport'),
(8, 'Drama');

-- --------------------------------------------------------

--
-- Table structure for table `mails_box`
--

CREATE TABLE `mails_box` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender` enum('reviewer','admin') NOT NULL,
  `reviewers_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mails_box`
--

INSERT INTO `mails_box` (`id`, `conversation_id`, `sender`, `reviewers_id`, `subject`, `body`, `created_at`, `is_read`) VALUES
(32, 21, 'reviewer', 27, 'Make an Account', 'Can you help my friend make an account for him, please?', '2026-04-12 14:05:49', 1),
(33, 21, 'admin', 27, 'Re: Make an Account', 'Sure', '2026-04-12 14:06:07', 1),
(35, 21, 'reviewer', 27, 'Re: Make an Account', 'Thks', '2026-04-12 14:14:44', 1),
(37, 21, 'admin', 27, 'Re: Make an Account', 'You got it dude', '2026-04-12 14:21:14', 1),
(38, 21, 'admin', 27, 'Re: Make an Account', 'Have a nice day!!', '2026-04-12 14:21:30', 1),
(41, 26, 'reviewer', 27, 'Add Film', 'Add Warrior', '2026-04-14 07:28:48', 1),
(42, 26, 'admin', 27, 'Re: Add Film', 'Oke', '2026-04-14 07:29:13', 1),
(43, 27, 'admin', 27, 'NEW MOVIE 🐦‍🔥🐦‍🔥', 'Warrior has been updated to the movie list. Make youself at home and enjoy it!!', '2026-04-14 10:05:00', 1),
(44, 28, 'reviewer', 27, 'Add Film', 'I want to see a film called Thor: Ragnarok', '2026-04-14 18:55:20', 0),
(45, 29, 'reviewer', 27, 'A Request for a Movie', 'I would love to see a new movie about passion and love', '2026-04-15 19:39:22', 1),
(46, 29, 'admin', 27, 'Re: A Request for a Movie', 'Sure', '2026-04-15 19:46:55', 1),
(47, 30, 'admin', 27, 'NEW MOVIE 🐦‍🔥🐦‍🔥', 'Big George Foreman is on the way! Hope you enjoy it', '2026-04-15 19:54:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 10),
  `review_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reviewers_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `review_text`, `rating`, `review_date`, `reviewers_id`, `film_id`) VALUES
(1, 'The infinity time in space gave me the feeling i\'d never have before', 5, '2026-03-27 17:06:42', 2, 2),
(2, 'This Action Movie is one of the best DC movie ever!🔥', 5, '2026-03-27 17:06:42', 3, 3),
(3, 'A beautiful story about space and humanity.', 5, '2026-03-27 15:29:00', 4, 4),
(4, 'What an amazing movie', 4, '2026-04-12 00:48:26', 5, 20),
(5, 'This movie had brought me to the mysterious feeling and I\'d never experienced before', 5, '2026-03-27 17:06:42', 6, 1),
(6, 'I am Batmannnn!!!', 5, '2026-03-27 17:06:42', 2, 3),
(7, 'hahaha this movie has made my day!! 🤣🤣', 5, '2026-03-27 17:06:42', 6, 6),
(14, 'Amazing choreography ', 5, '2026-04-11 14:25:02', 5, 2),
(16, 'I am a Marvel Fan, and this movie has me emotionally in tears of joy', 5, '2026-04-05 01:00:58', 4, 13),
(17, 'Boring', 1, '2026-04-05 01:54:26', 14, 1),
(22, 'As a fan of Spiderman and Andrew, he must have a trilogy for himself. This movie was too good to be abandoned 😖😖', 5, '2026-04-12 20:16:37', 21, 26),
(26, 'Crime Syndicate 🫆', 3, '2026-04-12 17:06:42', 21, 25),
(28, 'great character as a great actor like Christian Bale ', 5, '2026-04-12 20:18:01', 27, 3),
(29, 'Poor Rose 😖😖', 5, '2026-04-12 20:20:37', 3, 5),
(30, 'After this, I\'d might be crazy like him 🃏🤡🤡', 4, '2026-04-12 21:54:38', 27, 25),
(31, 'This movie has always been on my movie night, go go Rocky🥊🥊🍿🍿', 5, '2026-04-12 22:06:26', 27, 14),
(33, 'No Tony 🥹🥹', 5, '2026-04-13 22:22:31', 2, 13),
(34, 'What a great movie for a stress relief', 4, '2026-04-13 22:24:29', 6, 6),
(35, 'Nah, Jack would still be alive if Rose hadn\'t jumped back on the ship :))))', 3, '2026-04-14 23:44:27', 27, 5);

-- --------------------------------------------------------

--
-- Table structure for table `reviewers`
--

CREATE TABLE `reviewers` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviewers`
--

INSERT INTO `reviewers` (`id`, `username`, `phone_number`, `email`, `created_at`) VALUES
(1, 'Alice Nguyennn', '0901234567', 'alice@gmail.com', '2026-03-06 17:28:51'),
(2, 'Brian Tran', '0902345678', 'brian@gmail.com', '2026-03-06 17:28:51'),
(3, 'Charlie Pham', '0903456789', 'charlie@gmail.com', '2026-03-06 17:28:51'),
(4, 'David Le', '0904567890', 'david@gmail.com', '2026-03-06 17:28:51'),
(5, 'Emma Vu', '0905678901', 'emma@gmail.com', '2026-03-06 17:28:51'),
(6, 'Frank Hoang', '0906789012', 'frank@gmail.com', '2026-03-06 17:28:51'),
(14, 'Victor Lam', '09659055941', 'victor123@gmail.com', '2026-04-05 01:46:05'),
(15, 'Jason Todd', '0834567812', 'jason@gmail.com', '2026-04-05 01:55:28'),
(21, 'Bao Willy', '1234567890', 'willy@gmail.com', '2026-04-07 17:28:00'),
(27, 'Dave Tran', '1234567890', 'dave@gmail.com', '2026-04-11 23:51:59'),
(30, 'Sammy Dutch', '0998123531', 'sammy@gmail.com', '2026-04-15 16:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `stars`
--

CREATE TABLE `stars` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stars`
--

INSERT INTO `stars` (`id`, `name`) VALUES
(19, 'Aaron Eckhart'),
(59, 'Andrew Garfield'),
(15, 'Anne Hathaway'),
(48, 'Benedict Cumberbatch'),
(21, 'Billy Zane'),
(57, 'Brett Cullen'),
(42, 'Burt Young'),
(43, 'Carl Weathers'),
(9, 'Chris Evans'),
(28, 'Chris Hemsworth'),
(2, 'Chris Tucker'),
(17, 'Christian Bale'),
(33, 'Cillian Murphy'),
(23, 'Dolph Lundgren'),
(13, 'Elliot Page'),
(60, 'Emma Stone'),
(45, 'Evangeline Lilly'),
(71, 'Forest Whitaker'),
(56, 'Frances Conroy'),
(40, 'Frances Fisher'),
(36, 'Gary Oldman'),
(18, 'Heath Ledger'),
(24, 'Ian McKellen'),
(1, 'Jackie Chan'),
(50, 'James Nesbitt'),
(68, 'Jasmine Mathews'),
(30, 'Jeremy Renner'),
(16, 'Jessica Chastain'),
(53, 'Joaquin Phoenix'),
(62, 'Joel Edgerton'),
(70, 'John Magaro'),
(66, 'Jon Bernthal'),
(63, 'Jonah Hill'),
(12, 'Joseph Gordon-Levitt'),
(20, 'Kate Winslet'),
(39, 'Kathy Bates'),
(52, 'Ken Leung'),
(49, 'Ken Stott'),
(32, 'Ken Watanabe'),
(67, 'Khris Davis'),
(65, 'Kyle Chandler'),
(46, 'Lee Pace'),
(11, 'Leonardo DiCaprio'),
(47, 'Luke Evans'),
(37, 'Maggie Gyllenhaal'),
(64, 'Margot Robbie'),
(10, 'Mark Ruffalo'),
(25, 'Martin Freeman'),
(35, 'Matt Damon'),
(14, 'Matthew McConaughey'),
(34, 'Michael Caine'),
(27, 'Michelle Rodriguez'),
(38, 'Morgan Freeman'),
(61, 'Nick Nolte'),
(51, 'Orlando Bloom'),
(44, 'Richard Armitage'),
(54, 'Robert De Niro'),
(8, 'Robert Downey Jr.'),
(5, 'Sam Worthington'),
(29, 'Scarlett Johansson'),
(58, 'Shea Whigham'),
(7, 'Sigourney Weaver'),
(26, 'Stephen Lang'),
(69, 'Sullivan Jones'),
(41, 'Sylvester Stallone'),
(22, 'Talia Shire'),
(31, 'Tom Hardy'),
(3, 'Tom Wilkinson'),
(4, 'Tzi Ma'),
(55, 'Zazie Beetz'),
(6, 'Zoe Saldana');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_genre_id` (`genre_id`),
  ADD KEY `director_id` (`director_id`);

--
-- Indexes for table `film_stars`
--
ALTER TABLE `film_stars`
  ADD PRIMARY KEY (`film_id`,`star_id`),
  ADD KEY `star_id` (`star_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mails_box`
--
ALTER TABLE `mails_box`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `reviewers_id` (`reviewers_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_reviewers` (`reviewers_id`),
  ADD KEY `fk_review_film` (`film_id`);

--
-- Indexes for table `reviewers`
--
ALTER TABLE `reviewers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `stars`
--
ALTER TABLE `stars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `directors`
--
ALTER TABLE `directors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mails_box`
--
ALTER TABLE `mails_box`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `reviewers`
--
ALTER TABLE `reviewers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `stars`
--
ALTER TABLE `stars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `film`
--
ALTER TABLE `film`
  ADD CONSTRAINT `film_ibfk_1` FOREIGN KEY (`director_id`) REFERENCES `directors` (`id`),
  ADD CONSTRAINT `fk_genre_id` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`);

--
-- Constraints for table `film_stars`
--
ALTER TABLE `film_stars`
  ADD CONSTRAINT `film_stars_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `film_stars_ibfk_2` FOREIGN KEY (`star_id`) REFERENCES `stars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mails_box`
--
ALTER TABLE `mails_box`
  ADD CONSTRAINT `mails_box_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `mails_box_ibfk_2` FOREIGN KEY (`reviewers_id`) REFERENCES `reviewers` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_film` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_reviewers` FOREIGN KEY (`reviewers_id`) REFERENCES `reviewers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
