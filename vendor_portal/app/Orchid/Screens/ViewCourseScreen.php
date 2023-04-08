<?php

namespace App\Orchid\Screens;

use App\Models\Course;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\ViewCourseLayout;
use Exception;
use Orchid\Support\Facades\Toast;

class ViewCourseScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'courses' => Course::orderBy('ordering', 'asc')->where('category', 4)->paginate(20),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Courses';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ViewCourseLayout::class,
        ];
    }
    
    public function redirect( $course, $type){

        if($type == "section"){
            return redirect()->route('platform.courseSection.list',  $course);
        }
    }
}
