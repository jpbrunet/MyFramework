<?php

use App\Framework\Utils\Generator;
use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // SEEDING CATEGORIES
        $data = [];
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 5; ++$i) {
            $name = $faker->catchPhrase;
//            var_dump($name); die();
            $slug = Generator::createSlug($name);
            $data[] = [
                'name' => $name,
                'slug' => $slug,
            ];
        }
        $this->table('categories')
            ->insert($data)
            ->save();

        // SEEDING POSTS
        $data = [];
        for ($i = 0; $i < 100; ++$i) {
            $name = $faker->catchPhrase;
            $slug = Generator::createSlug($name);
            $date = $faker->unixTime('now');
            $data[] = [
                'name' => $name,
                'slug' => $slug,
                'category_id' => rand(1, 5),
                'content' => $faker->paragraphs(5, true),
                'created_at' => date('Y-m-d H:i:s', $date),
                'updated_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('posts')
            ->insert($data)
            ->save();
    }
}
