<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class gencrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gencrud {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a crud for a given model';

    /**
     * Execute the console command.
     *
     * @return int
     */

    protected $ignoredColumns = ['id', 'created_at', 'updated_at'];

    public function handle()
    {
        $model = $this->argument('model');
        $this->processAddController($model);
        $this->processModel($model);
        $this->processAddRequest($model);
        $this->processRepository($model);
        $this->processIndexView($model);
        $this->processAddView($model);
    }

    protected function tableColumns($model)
    {
        $model = '\\App\Models\\' . $model;
        $modelObj = new $model;
        $table = $modelObj->getTable();
        $queryColumns = DB::select('describe ' . $table);
        $columns = [];
        $i = 0;
        foreach ($queryColumns as $column) {
            if (in_array($column->Field, $this->ignoredColumns))
                continue;
            $columns[$i]['name'] = $column->Field;
            $columns[$i]['type'] = $column->Type;
            $i++;
        }
        return $columns;
    }

    protected function createReplacements($model)
    {

        // example: blogComment for variables
        $variable = Str::camel($model);

        // exmple: blog_comments for path
        $path_name = Str::snake(Str::plural($model));

        // example: Blog Comment for messages
        $title = Str::headline($model);

        // example: Blog Comments for html titles etc
        $title_plural = Str::plural($title);

        return [
            'model' => $model,
            'variable' => $variable,
            'path_name' => $path_name,
            'title' => $title,
            'title_plural' => $title_plural
        ];
    }

    protected function replace($model, $content)
    {
        $find = [
            '{model}',
            '{variable}',
            '{path_name}',
            '{title}',
            '{title_plural}',
        ];
        $replace = $this->createReplacements($model);
        return str_replace($find, $replace, $content);
    }

    protected function processAddController($model)
    {
        $controllerPath = app_path('Http') . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Admin'  . DIRECTORY_SEPARATOR;
        $controllerFile = $model . 'Controller.php';
        if (!is_dir($controllerPath)) {
            $this->info('Creating ' . $controllerPath);
            mkdir($controllerPath, 0777, true);
        }

        $overwrite = false;
        if (file_exists($controllerPath . $controllerFile)) {
            $overwrite = $this->confirm('The controller ' . $controllerFile . ' already exist. Do you want to overwrite?');
        }
        if (!file_exists($controllerPath . $controllerFile) || $overwrite) {
            $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs');
            $stubContent = file_get_contents($stubsPath . DIRECTORY_SEPARATOR . 'controller.stub');
            $replaced = $this->replace($model, $stubContent);
            file_put_contents($controllerPath . $controllerFile, $replaced);
        }
    }

    protected function processAddRequest($model)
    {
        $requestPath = app_path('Http') . DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR;
        $requestFile = 'Store' . $model . 'Request.php';
        if (!is_dir($requestPath)) {
            $this->info('Creating ' . $requestPath);
            mkdir($requestPath, 0777, true);
        }

        $overwrite = false;
        if (file_exists($requestPath . $requestFile)) {
            $overwrite = $this->confirm('The form request ' . $requestFile . ' already exist. Do you want to overwrite?');
        }
        if (!file_exists($requestPath . $requestFile) || $overwrite) {
            $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs');
            $stubContent = file_get_contents($stubsPath . DIRECTORY_SEPARATOR . 'request.stub');
            $replaced = $this->replace($model, $stubContent);
            $rules = $this->addRules($model);
            $replaced = str_replace('{rules}', $rules, $replaced);
            file_put_contents($requestPath . $requestFile, $replaced);
        }
    }

    public function addRules($model)
    {
        $columns = $this->tableColumns($model);
        $rules = '';
        foreach ($columns as $column) {
            $rules .= '\'' . $column['name'] . '\' => \'required\',' . "\n";
        }
        return $rules;
    }


    protected function processModel($model)
    {
        $modelPath = app_path('Models') . DIRECTORY_SEPARATOR;
        $modelFile = $model . '.php';

        $overwrite = false;
        if (file_exists($modelPath . $modelFile)) {
            $overwrite = $this->confirm('The model ' . $modelFile . ' already exist. Do you want to overwrite?');
        }

        if (!file_exists($modelPath . $modelFile) || $overwrite) {
            $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs');
            $stubContent = file_get_contents($stubsPath . DIRECTORY_SEPARATOR . 'model.stub');
            $replaced = $this->replace($model, $stubContent);
            file_put_contents($modelPath . $modelFile, $replaced);
        }
    }

    protected function processRepository($model)
    {
        $repositoryPath = app_path('Repositories') . DIRECTORY_SEPARATOR;
        $repositoryFile = $model . 'Repository.php';
        if (!is_dir($repositoryPath)) {
            $this->info('Creating ' . $repositoryPath);
            mkdir($repositoryPath, 0777, true);
        }


        $overwrite = false;
        if (file_exists($repositoryPath . $repositoryFile)) {
            $overwrite = $this->confirm('The repository ' . $repositoryFile . ' already exist. Do you want to overwrite?');
        }

        if (!file_exists($repositoryPath . $repositoryFile) || $overwrite) {
            $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs');
            $stubContent = file_get_contents($stubsPath . DIRECTORY_SEPARATOR . 'repository.stub');
            $replaced = $this->replace($model, $stubContent);
            file_put_contents($repositoryPath . $repositoryFile, $replaced);
        }
    }

    protected function processIndexView($model)
    {
        $indexBladeFile = 'index.blade.php';
        $moduleFolder = Str::snake(Str::plural($model));
        $folderPath = resource_path('views') . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleFolder;

        if (!is_dir($folderPath)) {
            $this->info('Creating ' . $folderPath);
            mkdir($folderPath, 0777, true);
        }

        $viewPath = $folderPath . DIRECTORY_SEPARATOR . $indexBladeFile;
        $overwrite = false;

        if (file_exists($viewPath)) {
            $overwrite = $this->confirm('The view file ' . $indexBladeFile . ' already exist. Do you want to overwrite?');
        }

        if (!file_exists($viewPath) || $overwrite) {
            $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs');
            $stubContent = file_get_contents($stubsPath . DIRECTORY_SEPARATOR . 'index.blade.stub');
            $th = $this->buildTh($model, $stubContent);
            $td = $this->buildTd($model, $stubContent);
            $replaced = $this->replace($model, $stubContent);
            $replaced = str_replace('{table_headings}', $th, $replaced);
            $replaced = str_replace('{table_tr}', $td, $replaced);
            file_put_contents($viewPath, $replaced);
        }
    }

    public function buildTh($model, $content)
    {
        $columns = $this->tableColumns($model);
        $th = '';
        foreach ($columns as $column) {
            $th .= '<th>' . Str::headline($column['name']) . '</th>' . "\n";
        }
        return $th;
    }

    public function buildTd($model, $content)
    {
        $columns = $this->tableColumns($model);
        $td = '';
        foreach ($columns as $column) {
            $td .= '<td>@{{ item.' . $column["name"] . '}}</td>' . "\n";
        }
        return $td;
    }

    protected function processAddView($model)
    {
        $addBladeFile = 'add.blade.php';
        $moduleFolder = Str::snake(Str::plural($model));
        $folderPath = resource_path('views') . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleFolder;

        if (!is_dir($folderPath)) {
            $this->info('Creating ' . $folderPath);
            mkdir($folderPath, 0777, true);
        }

        $viewPath = $folderPath . DIRECTORY_SEPARATOR . $addBladeFile;
        $overwrite = false;

        if (file_exists($viewPath)) {
            $overwrite = $this->confirm('The view file ' . $addBladeFile . ' already exist. Do you want to overwrite?');
        }

        if (!file_exists($viewPath) || $overwrite) {
            $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs');
            $stubContent = file_get_contents($stubsPath . DIRECTORY_SEPARATOR . 'add.blade.stub');
            $fields = $this->buildFormFields($model);
            $replaced = $this->replace($model, $stubContent);
            $replaced = str_replace('{fields}', $fields, $replaced);
            file_put_contents($viewPath, $replaced);
        }
    }

    public function buildFormFields($model)
    {
        $stubsPath = app_path('Console' . DIRECTORY_SEPARATOR . 'Commands' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR);
        $columns = $this->tableColumns($model);
        $replacements = $this->createReplacements($model);
        $fields = '';
        foreach ($columns as $column) {
            $title = Str::headline($column['name']);
            $id = Str::snake($column['name']);
            if (
                strstr($column['type'], 'varchar') ||
                strstr($column['type'], 'int')
            ) {
                $stubContent = file_get_contents($stubsPath . 'text.stub');

                $find = ['{title}', '{id}', '{variable}'];
                $replace = [$title, $id, $replacements['variable']];
                $fields .= str_replace($find, $replace, $stubContent);
            } else if (strstr($column['type'], 'enum')) {

                $stubContent = file_get_contents($stubsPath . 'select.stub');

                $datatype = str_replace('enum(', '', $column['type']);
                $datatype = str_replace('\'', '', $datatype);
                $datatype = str_replace(')', '', $datatype);
                $options = explode(',', $datatype);

                $optionString = '';
                foreach ($options as $option) {
                    $optionLabel = Str::headline($option);
                    $optionString .= '<option value="' . $option . '" @if($' . $replacements['variable'] . ' &&  $' . $replacements['variable'] . '->' . $id . ' == ' . '"' . $option . '") selected="selected" @endif>' . $optionLabel . '</option>';
                }
                $find = ['{title}', '{id}', '{options}'];
                $replace = [$title, $id, $optionString];
                $fields .= str_replace($find, $replace, $stubContent);
            }
        }
        return $fields;
    }
}
