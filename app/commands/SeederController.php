<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;


/**
 * Class SeederController
 * @package app\commands
 */
class SeederController extends Controller
{
    /**
     * @return int Exit code
     */
    public function actionIndex()
    {

        \Yii::$app->db->createCommand('SET foreign_key_checks = 0;')->execute();

        $seeder = new \tebazil\yii2seeder\Seeder();
        $generator = $seeder->getGeneratorConfigurator();
        $faker = $generator->getFakerConfigurator();

        $seeder->table('book')->columns([
            'id',
            'name' => $faker->text(20),
            'cover' => $faker->randomElement(['soft', 'hard']),
            'circulation' => $faker->randomElement(['1000', '5000', '10000']),
        ])->rowQuantity(100);

        $seeder->table('category')->columns([
            'id',
            'name' => $faker->text(20),
        ])->rowQuantity(20);

        $count = 2000;
        while ($count--) {
            $book_id = mt_rand(1, 100);
            $category_id = mt_rand(1, 20);
            $array[$book_id . '-' . $category_id] = [$book_id, $category_id];
        }

        $seeder->table('book_category')
            ->data($array, ['book_id', 'category_id'])
            ->rowQuantity(count($array));

        $seeder->table('surfer')->columns([
            'datetime' => function () {
                return date('Y-m-d H:i:s', time() - mt_rand(1, 24 * 60 * 60));
            },
            'status' => $faker->randomElement(['1', '2']),
        ])->rowQuantity(1000);

        try {
            $seeder->refill();
        } catch (\Throwable $t) {
            echo $t->getMessage() . PHP_EOL;
        }

        \Yii::$app->db->createCommand('SET foreign_key_checks = 1;')->execute();

        return ExitCode::OK;
    }
}
