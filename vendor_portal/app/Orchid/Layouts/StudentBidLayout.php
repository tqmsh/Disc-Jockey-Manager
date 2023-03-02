<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use App\Models\Events;
use App\Models\Region;
use App\Models\Student;
use Orchid\Support\Color;
use App\Models\VendorPackage;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\Button;

class StudentBidLayout extends Table
{
    public $studentBid;
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'studentBids';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
        TD::make()
                ->align(TD::ALIGN_LEFT)
                ->render(function($studentBid){
                    return 
                    ($studentBid->status == 0) ? Button::make('Edit Bid')->type(Color::PRIMARY())->method('editBid', ['bidId' => $studentBid->id, 'type' => 'student'])->icon('pencil') 
                    : '';
                }), 

            TD::make('status', 'Status')
                ->render(function($studentBid){
                    return 
                        ($studentBid->status == 0) ? '<i class="text-warning">●</i> Pending' 
                        : (($studentBid->status == 1) ? '<i class="text-success">●</i> Approved' 
                        : '<i class="text-danger">●</i> Rejected');
                }),

            TD::make('student_id', 'Student ID')
                ->render(function($studentBid){
                    return e($studentBid->student_id === null ? 'Student no longer exists': $studentBid->student_id);
                }),

            TD::make('school_name', 'School')->width('200px')
                ->render(function($studentBid){
                    return e($studentBid->school_name === null ? 'School no longer exists' : $studentBid->school_name);
                }),

            TD::make('region_id', 'Region')
                ->render(function($studentBid){
                    return e(Region::find($studentBid->region_id)->name);
                }), 

            TD::make('package_id', 'Package')
                ->render(function($studentBid){
                    return e($studentBid->package_id === null ? 'Package no longer exists': VendorPackage::find($studentBid->package_id)->package_name);
                }), 


            TD::make('notes', 'Notes')
                ->render(function($studentBid){
                    return e($studentBid->notes);
                }), 

            TD::make('contact_instructions', 'Contact Info')
                ->render(function($studentBid){
                    return e($studentBid->contact_instructions);
                }), 
            
            TD::make('created_at', 'Created At')
                ->render(function($studentBid){
                    return e($studentBid->created_at);
                }), 
        ];
    }
}
