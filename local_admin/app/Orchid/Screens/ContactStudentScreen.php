<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\SimpleMDE;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactStudentScreen extends Screen
{
    public $students;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'students' => User::whereIn('id', Student::where('school_id', Auth::user()->localadmin->school_id)->get('user_id'))->get()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Contact Students Directly';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.student.list')
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
                Layout::rows([
                    Input::make('subject')
                        ->title('Subject')
                        ->placeholder('Message subject line')
                        ->help('Enter the subject line for your message'),
                        
                    Select::make('users.')
                        ->title('Recipients')
                        ->multiple()
                        ->placeholder('Email addresses')
                        ->help('Enter the users that you would like to send this message to.')
                        ->options(function (){
                            $students = [];
                            foreach($this->query()['students'] as $student){
                                $students[$student->email] = $student->firstname . ' '  . $student->lastname;
                            }
                            return $students;
                        }),
                    SimpleMDE::make('content')
                            ->title('Content')
                            ->toolbar(["text", "color", "header", "list", "format", "align", "link", ])
                            ->placeholder('Insert text here ...')
                            ->help('Add the content for the message that you would like to send.'),

                    Button::make('Send')
                        ->method('sendMessage')
                        ->icon('paper-plane')
                        ->type(Color::DARK())
                ])
        ];
    }

    public function sendMessage(Request $request)
    {
        try {
            $data = $request->validate([
                'subject' => 'required|min:6|max:50',
                'users'   => 'required',
                'content' => 'required|min:10',
            ]);

            $data['sender'] = Auth::user();

            Mail::send(
                'emails.generalEmail', $data, 
                function (Message $message) use ($request) {
                $message->subject($request->get('subject'));

                foreach ($request->get('users') as $email) {
                    $message->to($email);
                }
            });

            Toast::info('Your email message has been sent successfully.');

        } catch (\Exception $e) {
            Toast::error($e->getMessage());
        }
    }
}


