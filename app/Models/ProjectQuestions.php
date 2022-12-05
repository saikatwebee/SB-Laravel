<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $project_no
 * @property string $customer_name
 * @property string $project_title
 * @property string $designation
 * @property string $company_name
 * @property string $industry_category
 * @property string $location
 * @property string $language
 * @property string $turnover
 * @property string $industry_vertical
 * @property string $products_manufactured
 * @property string $to_manufacture
 * @property string $project_type
 * @property string $problem_type
 * @property string $budget
 * @property string $timeline
 * @property string $project_stage
 * @property string $requirement
 * @property string $type_of_consultant
 * @property string $comments
 * @property string $skills
 * @property string $experience
 * @property string $status
 * @property string $manager
 * @property string $date_added
 * @property int $draft
 * @property Problem $problem
 */
class ProjectQuestions extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['project_no', 'customer_name', 'project_title', 'designation', 'company_name', 'industry_category', 'location', 'language', 'turnover', 'industry_vertical', 'products_manufactured', 'to_manufacture', 'project_type', 'problem_type', 'budget', 'timeline', 'project_stage', 'requirement', 'type_of_consultant', 'comments', 'skills', 'experience', 'status', 'manager', 'date_added', 'draft'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function problem()
    {
        return $this->belongsTo('App\Models\Problem', 'project_no');
    }
}
