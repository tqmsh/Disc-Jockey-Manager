<?php

namespace App\Orchid\Screens;
use DateTime;
use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\School;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\DB;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;

class CreateSchoolScreen extends Screen
{
    public $requiredFields = ['nces_id', 'country', 'school_name', 'school_board', 'county', 'address', 'city_municipality', 'state_province', 'zip_postal', 'phone_number', 'total_students', 'school_data', 'region'];
    public $school;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(School $school, Request $request): iterable
    {
        $school->nces_id = $request->input('nces_id') ?? "";
        $school->school_name = $request->input('school_name') ?? "";
        $school->country = $request->input('country') ?? "";
        $school->state_province = $request->input('state_province') ?? "";
        $school->region_id = intval($request->input('region_id')) ?? "";
        $school->school_board = $request->input('school_board') ?? "";
        $school->address = $request->input('address') ?? "";
        $school->zip_postal = $request->input('zip_postal') ?? "";
        $school->phone_number = $request->input('phone_number') ?? "";
        $school->fax = $request->input('fax') ?? "";
        $school->metropolitan_region = $request->input('metropolitan_region') ?? "";
        $school->city_municipality = $request->input('city_municipality') ?? "";
        $school->state_province = $request->input('state_province') ?? "";
        $school->county = $request->input('county') ?? "";
        $school->website = $request->input('website') ?? "";
        $school->school_data = $request->input('school_data') ?? "";
        $school->firstname = $request->input('firstname') ?? "";
        $school->lastname = $request->input('lastname') ?? "";
        $school->teacher_cell = $request->input('teacher_cell') ?? "";
        $school->teacher_email = $request->input('teacher_email') ?? "";
        $school->total_students = $request->input('total_students') ?? "";

        return [
            'school'=>$school
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add a New School';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Add')
                ->icon('plus')
                ->method('createSchool'),

            ModalToggle::make('Mass Import Schools')
                ->modal('massImportModal')
                ->method('massImport')
                ->icon('plus'),

            Link::make('Back')
                ->icon('arrow-left')
                ->route('platform.school.list')
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

            Layout::modal('massImportModal',[

                Layout::rows([

                    Input::make('school_csv')
                        ->type('file')
                        ->title('File must be in csv format. Ex. schools.csv')
                        ->help('The csv file MUST HAVE these fields and they need to be named accordingly to successfully import the schools: <br>
                            • nces_id <br>
                            • school_name <br>
                            • school_board <br>
                            • county <br>
                            • address <br>
                            • city_municipality <br>
                            • country <br>
                            • state_province <br>
                            • zip_postal <br>
                            • metropolitan_region <br>
                            • phone_number <br>
                            • fax <br>
                            • website <br>
                            • total_students <br>
                            • school_data <br>
                            • region<br>'),
                        Link::make('Download Sample CSV')
                            ->icon('download')
                            ->href('/sample_schools_upload.csv')
                ]),
            ])
            ->title('Mass Import Schools')
            ->applyButton('Import')
            ->withoutCloseButton(),

            Layout::rows([
                Input::make('nces_id')
                    ->title('NCES ID')
                    ->type('number')
                    ->horizontal()
                    ->required()
                    ->placeholder('Ex. 546879123')
                    ->value($this->school->nces_id),

                Input::make('school_name')
                    ->title('School Name')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Colonel By Secondary School')
                    ->value($this->school->school_name),

                Input::make('country')
                    ->title('Country')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Canada')
                    ->value($this->school->country),


            Input::make('state_province')
                    ->title('State/Province')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Ontario')
                    ->value($this->school->state_province),


                Select::make('region_id')
                    ->title('Region')
                    ->required()
                    ->horizontal()
                    ->empty('Start typing to Search...')
                    ->fromQuery(Region::query(), 'name')
                    ->value($this->school->region_id),

                Input::make('school_board')
                    ->title('School Board')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. OCDSB')
                    ->value($this->school->school_board),

                Input::make('address')
                    ->title('Address')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. 2381 Ogilvie Rd')
                    ->value($this->school->address),

                Input::make('zip_postal')
                    ->title('Zip/Postal')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. K1J 7N4')
                    ->value($this->school->zip_postal),

                Input::make('phone_number')
                    ->title('Phone Number')
                    ->type('text')
                    ->mask('(999) 999-9999')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. (613) 859-5863')
                    ->value($this->school->phone_number),

                Input::make('fax')
                    ->title('Fax Number')
                    ->type('number')
                    ->horizontal()
                    ->placeholder('Ex. 546879123')
                    ->value($this->school->fax),

                Input::make('metropolitan_region')
                    ->title('Metropolitan Region')
                    ->type('text')
                    ->horizontal()
                    ->required()
                    ->placeholder('Ex. Greater Ottawa Metropolitan Area')
                    ->value($this->school->metropolitan_region),

                Input::make('city_municipality')
                    ->title('City/Municipality')
                    ->type('text')
                    ->horizontal()
                    ->required()
                    ->placeholder('Ex. Ottawa')
                    ->value($this->school->city_municipality),

                Input::make('county')
                    ->title('County')
                    ->type('text')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. Suffolk County')
                    ->value($this->school->county),

                Input::make('website')
                    ->title('Website')
                    ->type('url')
                    ->horizontal()
                    ->placeholder('Ex. https://colonelby.com')
                    ->value($this->school->website),

                Input::make('school_data')
                    ->title('School Data')
                    ->type('text')
                    ->horizontal()
                    ->placeholder('Ex. www.colonelby.com?Search=1&InstName=newton&State=25&SchoolType=1')
                    ->value($this->school->school_data),

                Input::make('firstname')
                    ->title('Teacher First Name')
                    ->type('text')
                    ->horizontal()
                    ->required()
                    ->placeholder('Ex. John')
                    ->value($this->school->firstname),

                Input::make('lastname')
                    ->title('Teacher Last Name')
                    ->type('text')
                    ->horizontal()
                    ->required()
                    ->placeholder('Ex. Doe')
                    ->value($this->school->lastname),

                Input::make('teacher_email')
                    ->title('Teacher Email')
                    ->type('email')
                    ->required()
                    ->horizontal()
                    ->placeholder('Ex. johndoe@gmail.com')
                    ->value($this->school->teacher_email),

                Input::make('teacher_cell')
                    ->title('Teacher Contact Number')
                    ->type('text')
                    ->required()
                    ->mask('(999) 999-9999')
                    ->horizontal()
                    ->placeholder('Ex. (613) 852-4563')
                    ->value($this->school->teacher_cell),

                Input::make('total_students')
                    ->title('Total Students')
                    ->type('number')
                    ->horizontal()
                    ->placeholder('Ex. 1024')
                    ->value($this->school->total_students),
            ]),
        ];
    }

    //this method will add a single school to the database
    public function createSchool(Request $request){
        try{

            //check for duplicate schools
            if($this->validSchool($request) && $this->validEmail($request)){

                //collect the school table values and user table values
                $userTableFields = $this->getUserFields($request);
                $schoolTableFields = $this->getSchoolFields($request);

                //make the user first, then get the id and insert that along with other fields in the school table
                $user = User::create($userTableFields);

                $schoolTableFields['teacher_id'] = $user->id;

                School::create($schoolTableFields);

                Toast::success('You have successfully created ' . $request->input('school_name') . '.');

                return redirect()->route('platform.school.list');

            }else{

                //duplicate school found

                Toast::error('School already exists or email already in use.');
                return redirect()->route('platform.school.create',request(['nces_id', 'school_name', 'country', 'state_province', 'region_id', 'school_board', 'address', 'zip_postal', 'phone_number', 'fax', 'metropolitan_region', 'city_municipality', 'county', 'website', 'school_data', 'firstname', 'lastname', 'teacher_email', 'teacher_cell', 'total_students' ]));

            }

        }catch(Exception $e){

            Alert::error('There was an error creating this school. Error Code: ' . $e->getMessage());
            return redirect()->route('platform.school.create',request(['nces_id', 'school_name', 'country', 'state_province', 'region_id', 'school_board', 'address', 'zip_postal', 'phone_number', 'fax', 'metropolitan_region', 'city_municipality', 'county', 'website', 'school_data', 'firstname', 'lastname', 'teacher_email', 'teacher_cell', 'total_students' ]));
        }
    }

    //this method will mass import schools from a csv file
    public function massImport(Request $request){

        try{

            $path = $this->validFile($request);

            $schools = $this->csvToArray($path);

            $data = [];

            $keys = array_keys($schools[0]);

            //check if the user has the required values
            foreach($this->requiredFields as $field){

                if(!in_array($field, $keys)){
                        Toast::error('"' . $field . '"' . 'is missing in your csv file.'); return;
                }
            }

            //loop through the array of schools and re-write the keys to insert in db
            for ($i = 0; $i < count($schools); $i ++){

                $region_id = Region::firstOrCreate(['name' => $schools[$i]['region']])->id;
                $data[] = [
                    'nces_id' => ($schools[$i]['nces_id']),
                    'school_name' => $schools[$i]['school_name'],
                    'school_board' => $schools[$i]['school_board'],
                    'county' => $schools[$i]['county'],
                    'address' => $schools[$i]['address'],
                    'city_municipality' => $schools[$i]['city_municipality'],
                    'state_province' => $schools[$i]['state_province'],
                    'zip_postal' => $schools[$i]['zip_postal'],
                    'phone_number' => $schools[$i]['phone_number'],
                    'region_id' => $region_id,
                    'country' => $schools[$i]['country'],
                    'total_students' => $schools[$i]['total_students'],
                    'school_data' => $schools[$i]['school_data'],
                    'created_at' => new DateTime()
                ];
            }

            DB::table('schools')->insert($data);

            Toast::success('Schools imported successfully!');

            return redirect()->route('platform.school.list');

        }catch(Exception $e){

            Alert::error('There was an error mass importing the schools. Error Code: ' . $e->getMessage());
        }
    }

    private function validFile(Request $request){

        $path = '';

        if(!is_null($request->file('school_csv'))){

            $path = $request->file('school_csv')->getRealPath();

            if(!is_null($path)){

                $extension = $request->file('school_csv')->extension();

                if($extension != 'csv' && $extension != 'txt'){

                    Toast::error('Incorrect file type.'); return false;
                }else{

                    return $path;
                }

            } else{

                Toast::error('An error has occured.'); return;
            }

        } else{

            Toast::error('Upload a csv file to import vendors.'); return false;
        }
    }

    //this function will convert the csv file to an array
    private function csvToArray($filename = '', $delimiter = ','){

        if (!file_exists($filename) || !is_readable($filename)){
            Alert::error('There has been an error finding this file.');
            return;
        }

        $header = null;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== false){

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }

            fclose($handle);
        }

        return $data;
    }

    //this functions returns the values that need to be inserted in the school table in the db
    private function getSchoolFields($request){

        $schoolTableFields = $request->except(['firstname', 'lastname', 'teacher_email', 'teacher_cell']);

        return $schoolTableFields;
    }

    //this functions returns the values that need to be inserted in the user table in the db
    private function getUserFields($request){
        $userTableFields = [
            'name' => $request->input('firstname') . $request->input('lastname'),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'phonenumber' => $request->input('teacher_cell'),
            'email' => $request->input('teacher_email'),
            'role' => 5,
            'country' => $request->input('country'),
        ];

        return $userTableFields;
    }

    //check for duplicate emails
    private function validEmail($request){
        return count(User::where('email', $request->input('teacher_email'))->get()) == 0;
    }

    //this method checks for duplicate schools
    private function validSchool($request){

        return count(School::where('school_name', $request->input('school_name'))->where('county', $request->input('county'))->where('state_province', $request->input('state_province'))->where('country', $request->input('country'))->get()) == 0;
    }
}
