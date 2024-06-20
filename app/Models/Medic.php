<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use OpenApi\Annotations as OA;

/**
 * Class Medic
 * 
 * @author Steven Wijaya <steven.422023002@civitas.ukrida.ac.id>
 * 
 * @OA\Schema(
 *      description="Medical",
 *      title="Medical",
 *      required={"equipment_name", "category"},
 *      @OA\Xml(
 *          name="Medic")
 *  )
 */


 class Medic extends Model
 {
     // use HasFactory;
     use SoftDeletes;
     protected $table = 'medicals';
     protected $fillable = [
         'equipment_name',
         'category',
         'brand',
         'publication_year', 
         'cover',
         'description',
         'price',
         'created_at',
         'created_by',
         'updated_at',
         'updated_by',
     ];
 
     public function data_adder(){
         return $this->belongsTo(User::class, 'created_by', 'id');
     }
 
 }