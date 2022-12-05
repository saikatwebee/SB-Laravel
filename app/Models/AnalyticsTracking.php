<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $sb_first_typ
 * @property string $sb_first_src
 * @property string $sb_first_mdm
 * @property string $sb_first_cmp
 * @property string $sb_first_cnt
 * @property string $sb_first_trm
 * @property string $sb_current_typ
 * @property string $sb_current_src
 * @property string $sb_current_mdm
 * @property string $sb_current_cmp
 * @property string $sb_current_cnt
 * @property string $sb_current_trm
 * @property string $sb_first_add_fd
 * @property string $sb_first_add_ep
 * @property string $sb_first_add_rf
 * @property string $sb_current_add_fd
 * @property string $sb_current_add_ep
 * @property string $sb_current_add_rf
 * @property string $sb_session_pgs
 * @property string $sb_session_cpg
 * @property string $sb_udata_vst
 * @property string $sb_udata_uip
 * @property Customer $customer
 */
class AnalyticsTracking extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'analytics_tracking';

    /**
     * @var array
     */
    protected $fillable = ['customer_id', 'sb_first_typ', 'sb_first_src', 'sb_first_mdm', 'sb_first_cmp', 'sb_first_cnt', 'sb_first_trm', 'sb_current_typ', 'sb_current_src', 'sb_current_mdm', 'sb_current_cmp', 'sb_current_cnt', 'sb_current_trm', 'sb_first_add_fd', 'sb_first_add_ep', 'sb_first_add_rf', 'sb_current_add_fd', 'sb_current_add_ep', 'sb_current_add_rf', 'sb_session_pgs', 'sb_session_cpg', 'sb_udata_vst', 'sb_udata_uip'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id', 'customer_id');
    }
}
