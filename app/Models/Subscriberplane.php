<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 * @property string $title
 * @property int $cost
 * @property string $tax
 * @property int $plancost
 * @property int $apply
 * @property int $problem_posting
 * @property int $number_solution
 * @property int $validity
 * @property int $nda_confidentiality
 * @property string $coupon
 * @property int $discount
 * @property int $refer_discount
 * @property int $refer_wallet
 * @property int $product_display_details
 * @property int $company_website_display
 * @property int $company_phone_number_display
 * @property int $micro_site
 * @property string $date_added
 * @property int $status
 * @property CustomerPlane[] $customerPlanes
 * @property Invoice[] $invoices
 * @property PaymentLink[] $paymentLinks
 */
class Subscriberplane extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'subscriberplane';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['type', 'title', 'cost', 'tax', 'plancost', 'apply', 'problem_posting', 'number_solution', 'validity', 'nda_confidentiality', 'coupon', 'discount', 'refer_discount', 'refer_wallet', 'product_display_details', 'company_website_display', 'company_phone_number_display', 'micro_site', 'date_added', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerPlanes()
    {
        return $this->hasMany('App\Models\CustomerPlane');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'plan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentLinks()
    {
        return $this->hasMany('App\Models\PaymentLink', 'plan_id');
    }
}
