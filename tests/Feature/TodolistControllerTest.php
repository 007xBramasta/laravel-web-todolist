<?php

namespace Tests\Feature;

use Database\Seeders\TodoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TodolistControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from todos"); 
    }
    
    public function testTodolist()
    {
        $this->seed(TodoSeeder::class);

        $this->withSession([
            "user" => "bramasta",
        ])->get('/todolist')
           ->assertSeeText("1")
           ->assertSeeText("Bram")
           ->assertSeeText("2")
           ->assertSeeText("Albatio");     
    }

    public function testAddTodoFailed()
    {
        $this->withSession([
            "user" => "bramasta"
        ])->post("/todolist", [])
            ->assertSeeText("Todo is required");
    }

    public function testAddTodoSuccess()
    {
        $this->withSession([
            "user" => "bramasta"
        ])->post("/todolist", [
            "todo" => "Bram"
        ])->assertRedirect("/todolist");
    }

    public function testRemoveTodolist()
    {
        $this->withSession([
            "user" => "bramasta",
            "todolist" => [
                [
                    "id" => "1",
                    "todo" => "Bram"
                ],
                [
                    "id" => "2",
                    "todo" => "Albatio"
                ]
            ]
        ])->post("/todolist/1/delete")
            ->assertRedirect("/todolist");
    }
}
