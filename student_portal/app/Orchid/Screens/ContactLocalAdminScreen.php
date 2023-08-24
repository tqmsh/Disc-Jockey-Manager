<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use App\Models\Localadmin;
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

class ContactLocalAdminScreen extends Screen
{
    public $localadmins;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'localadmins' => User::whereIn('id', Localadmin::where('school_id', Auth::user()->student->school_id)->get('user_id'))->get()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Contact Your Prom Committees';
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
                ->route('platform.event.list')
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
                            $localadmins = [];
                            foreach($this->query()['localadmins'] as $localadmin){
                                $localadmins[$localadmin->email] = $localadmin->firstname . ' '  . $localadmin->lastname;
                            }
                            return $localadmins;
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


