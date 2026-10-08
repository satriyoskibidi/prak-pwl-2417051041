<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CrudUpdateDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_updated_and_deleted_by_uuid(): void
    {
        $kelas = Kelas::create(['nama_kelas' => 'IF-A']);
        $user = UserModel::create([
            'nama' => 'Nama Lama',
            'npm' => '2417051041',
            'kelas_id' => $kelas->id,
        ]);

        $this->assertTrue(Str::isUuid($user->id));
        $this->get(route('user.index'))
            ->assertOk()
            ->assertSee(route('user.update', $user), false)
            ->assertSee(route('user.destroy', $user), false);

        $this->put(route('user.update', $user), [
            'nama' => 'Nama Baru',
            'npm' => '2417051041',
            'kelas_id' => $kelas->id,
        ])
            ->assertRedirect(route('user.index'))
            ->assertSessionHas('success', 'Student updated successfully.');

        $this->assertDatabaseHas('user', [
            'id' => $user->id,
            'nama' => 'Nama Baru',
        ]);

        $this->delete(route('user.destroy', $user))
            ->assertRedirect(route('user.index'))
            ->assertSessionHas('success', 'Student deleted successfully.');

        $this->assertDatabaseMissing('user', ['id' => $user->id]);
    }

    public function test_subject_can_be_updated_and_deleted_by_uuid(): void
    {
        $subject = MataKuliah::create([
            'nama_mk' => 'Nama Lama',
            'sks' => 2,
        ]);

        $this->assertTrue(Str::isUuid($subject->id));
        $this->get(route('matakuliah.index'))
            ->assertOk()
            ->assertSee(route('matakuliah.update', $subject), false)
            ->assertSee(route('matakuliah.destroy', $subject), false);

        $this->put(route('matakuliah.update', $subject), [
            'nama_mk' => 'Nama Baru',
            'sks' => 3,
        ])
            ->assertRedirect(route('matakuliah.index'))
            ->assertSessionHas('success', 'Subject updated successfully.');

        $this->assertDatabaseHas('mata_kuliah', [
            'id' => $subject->id,
            'nama_mk' => 'Nama Baru',
            'sks' => 3,
        ]);

        $this->delete(route('matakuliah.destroy', $subject))
            ->assertRedirect(route('matakuliah.index'))
            ->assertSessionHas('success', 'Subject deleted successfully.');

        $this->assertDatabaseMissing('mata_kuliah', ['id' => $subject->id]);
    }
}
