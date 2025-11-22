<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\Fixtures;

final readonly class Movies
{
    /**
     * @return array<array{title: string, description: string, director: string}>
     */
    public static function all(): array
    {
        return [
            ['title' => 'Inception', 'description' => 'A skilled thief is given a chance at redemption if he can successfully perform inception, the act of planting an idea in someone\'s subconscious.', 'director' => 'Christopher Nolan'],
            ['title' => 'The Matrix', 'description' => 'A hacker discovers the world he lives in is a simulated reality and joins a rebellion to overthrow its controllers.', 'director' => 'The Wachowskis'],
            ['title' => 'The Godfather', 'description' => 'The aging patriarch of an organized crime dynasty transfers control of his empire to his reluctant son.', 'director' => 'Francis Ford Coppola'],
            ['title' => 'Notting Hill', 'description' => 'A British bookseller meets and falls in love with a famous American actress, navigating the challenges of fame and romance.', 'director' => 'Roger Michell'],
            ['title' => 'WALL-E', 'description' => 'A small waste-collecting robot inadvertently embarks on a space journey that will decide the fate of mankind.', 'director' => 'Andrew Stanton'],
            ['title' => 'Spirited Away', 'description' => 'A young girl enters a mysterious world of spirits and must find a way to rescue her parents and return home.', 'director' => 'Hayao Miyazaki'],
            ['title' => 'Jurassic Park', 'description' => 'During a preview tour, a theme park suffers a major power breakdown that allows its cloned dinosaur exhibits to run amok.', 'director' => 'Steven Spielberg'],
            ['title' => 'Interstellar', 'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.', 'director' => 'Christopher Nolan'],
            ['title' => 'The Shawshank Redemption', 'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', 'director' => 'Frank Darabont'],
            ['title' => 'Gladiator', 'description' => 'A former Roman General sets out to exact vengeance against the corrupt emperor who murdered his family and sent him into slavery.', 'director' => 'Ridley Scott'],
        ];
    }
}
