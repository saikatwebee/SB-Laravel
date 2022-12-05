<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $api_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $state
 * @property string $city
 * @property string $company_name
 * @property string $product_name
 * @property string $requirement
 * @property string $enquiry_type
 * @property string $api_date
 * @property int $status
 * @property string $date_added
 */
class Indiamart extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'indiamart';

    /**
     * @var array
     */
    protected $fillable = ['api_id', 'name', 'email', 'phone', 'address', 'state', 'city', 'company_name', 'product_name', 'requirement', 'enquiry_type', 'api_date', 'status', 'date_added'];
}
