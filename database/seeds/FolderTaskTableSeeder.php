<?php

use Illuminate\Database\Seeder;

class FolderTaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		factory(App\Folder::class, 5)->create();
		factory(App\Task::class, 20)->create();

		$folderIds = DB::table('folders')->pluck('id')->toArray();
		$taskIds = DB::table('tasks')->pluck('id')->toArray();

		foreach ((range(1, 5)) as $index) {
			DB::table('folder_task')->insert(
				[
					'folder_id' => $folderIds[array_rand($folderIds)],
					'task_id' => $taskIds[array_rand($taskIds)]
				]
			);
		}
    }
}
