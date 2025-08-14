<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user as job creator
        $admin = User::where('role', 'admin')->first();

        $jobs = [
            [
                'title' => 'Frontend Developer',
                'description' => '<p><strong>Job Description:</strong></p><p>We are looking for a skilled Frontend Developer to join our dynamic team. You will be responsible for developing user-friendly web interfaces using modern frameworks and technologies.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Computer Science or related field</li><li>3+ years experience with React.js or Vue.js</li><li>Proficient in HTML5, CSS3, and JavaScript</li><li>Experience with responsive design</li><li>Knowledge of Git version control</li></ul><p><strong>Benefits:</strong></p><ul><li>Health insurance</li><li>Flexible working hours</li><li>Professional development opportunities</li></ul>',
                'department' => 'IT',
                'company_name' => 'TechCorp Indonesia',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(2),
                'expired_date' => Carbon::now()->addDays(28),
                'location' => 'Jakarta',
                'salary_min' => 8000000,
                'salary_max' => 12000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Backend Developer',
                'description' => '<p><strong>Job Description:</strong></p><p>Join our backend team to build scalable and secure server-side applications. You will work with modern technologies and contribute to our microservices architecture.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Computer Science</li><li>4+ years experience with Laravel or Node.js</li><li>Strong knowledge of databases (MySQL, PostgreSQL)</li><li>Experience with RESTful APIs</li><li>Understanding of microservices architecture</li></ul><p><strong>Benefits:</strong></p><ul><li>Competitive salary</li><li>Remote work options</li><li>Annual bonuses</li></ul>',
                'department' => 'IT',
                'company_name' => 'DevSolutions',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(5),
                'expired_date' => Carbon::now()->addDays(25),
                'location' => 'Bandung',
                'salary_min' => 9000000,
                'salary_max' => 15000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => '<p><strong>Job Description:</strong></p><p>We are seeking a creative UI/UX Designer to create engaging and intuitive user experiences. You will work closely with product managers and developers to bring ideas to life.</p><p><strong>Requirements:</strong></p><ul><li>Degree in Design or related field</li><li>3+ years experience in UI/UX design</li><li>Proficiency in Figma, Adobe XD, or Sketch</li><li>Strong understanding of user-centered design principles</li><li>Portfolio showcasing mobile and web designs</li></ul><p><strong>Benefits:</strong></p><ul><li>Creative work environment</li><li>Latest design tools and equipment</li><li>Design conference sponsorship</li></ul>',
                'department' => 'Design',
                'company_name' => 'Creative Studios',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(1),
                'expired_date' => Carbon::now()->addDays(29),
                'location' => 'Jakarta',
                'salary_min' => 7000000,
                'salary_max' => 11000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Data Analyst',
                'description' => '<p><strong>Job Description:</strong></p><p>Analyze complex data sets to provide actionable insights for business decision-making. You will work with various stakeholders to understand data requirements and deliver comprehensive reports.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Statistics, Mathematics, or related field</li><li>2+ years experience in data analysis</li><li>Proficient in SQL and Python/R</li><li>Experience with data visualization tools (Tableau, Power BI)</li><li>Strong analytical and problem-solving skills</li></ul><p><strong>Benefits:</strong></p><ul><li>Data science training programs</li><li>Flexible schedule</li><li>Performance bonuses</li></ul>',
                'department' => 'Analytics',
                'company_name' => 'DataTech Solutions',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(3),
                'expired_date' => Carbon::now()->addDays(27),
                'location' => 'Surabaya',
                'salary_min' => 6500000,
                'salary_max' => 10000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Project Manager',
                'description' => '<p><strong>Job Description:</strong></p><p>Lead cross-functional teams to deliver projects on time and within budget. You will coordinate with various departments and ensure project milestones are met.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Business or related field</li><li>5+ years experience in project management</li><li>PMP certification preferred</li><li>Strong leadership and communication skills</li><li>Experience with Agile/Scrum methodologies</li></ul><p><strong>Benefits:</strong></p><ul><li>Leadership development programs</li><li>Company car</li><li>International training opportunities</li></ul>',
                'department' => 'Management',
                'company_name' => 'Global Enterprises',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(7),
                'expired_date' => Carbon::now()->addDays(23),
                'location' => 'Jakarta',
                'salary_min' => 12000000,
                'salary_max' => 18000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Marketing Specialist',
                'description' => '<p><strong>Job Description:</strong></p><p>Develop and execute marketing campaigns across digital and traditional channels. You will analyze market trends and create strategies to increase brand awareness.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Marketing or Communications</li><li>3+ years experience in digital marketing</li><li>Proficiency in Google Analytics and social media platforms</li><li>Strong content creation skills</li><li>Experience with marketing automation tools</li></ul><p><strong>Benefits:</strong></p><ul><li>Marketing budget for campaigns</li><li>Industry conference attendance</li><li>Creative freedom</li></ul>',
                'department' => 'Marketing',
                'company_name' => 'BrandMax Agency',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(4),
                'expired_date' => Carbon::now()->addDays(26),
                'location' => 'Yogyakarta',
                'salary_min' => 5500000,
                'salary_max' => 8500000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => '<p><strong>Job Description:</strong></p><p>Manage and optimize our cloud infrastructure and deployment pipelines. You will work to improve system reliability and deployment efficiency.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Computer Science or related field</li><li>4+ years experience with cloud platforms (AWS, GCP, Azure)</li><li>Strong knowledge of Docker and Kubernetes</li><li>Experience with CI/CD pipelines</li><li>Scripting skills in Python or Bash</li></ul><p><strong>Benefits:</strong></p><ul><li>Cloud certification sponsorship</li><li>Latest tech equipment</li><li>On-call compensation</li></ul>',
                'department' => 'IT',
                'company_name' => 'CloudTech Systems',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(6),
                'expired_date' => Carbon::now()->addDays(24),
                'location' => 'Jakarta',
                'salary_min' => 10000000,
                'salary_max' => 16000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Mobile App Developer',
                'description' => '<p><strong>Job Description:</strong></p><p>Develop native mobile applications for iOS and Android platforms. You will work on user-facing features and optimize app performance.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Computer Science</li><li>3+ years experience with React Native or Flutter</li><li>Knowledge of iOS and Android development</li><li>Experience with mobile app deployment</li><li>Understanding of mobile UI/UX principles</li></ul><p><strong>Benefits:</strong></p><ul><li>Mobile device allowances</li><li>Flexible work arrangements</li><li>App store publication support</li></ul>',
                'department' => 'IT',
                'company_name' => 'MobileFirst Labs',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(1),
                'expired_date' => Carbon::now()->addDays(29),
                'location' => 'Bali',
                'salary_min' => 8500000,
                'salary_max' => 13000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Human Resources Manager',
                'description' => '<p><strong>Job Description:</strong></p><p>Oversee all HR functions including recruitment, employee relations, and policy development. You will ensure compliance with labor laws and foster a positive work culture.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Human Resources or Psychology</li><li>5+ years experience in HR management</li><li>Strong knowledge of Indonesian labor laws</li><li>Experience with HR systems and tools</li><li>Excellent interpersonal skills</li></ul><p><strong>Benefits:</strong></p><ul><li>HR certification programs</li><li>Employee wellness programs</li><li>Professional development budget</li></ul>',
                'department' => 'Human Resources',
                'company_name' => 'People Solutions Inc',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(8),
                'expired_date' => Carbon::now()->addDays(22),
                'location' => 'Jakarta',
                'salary_min' => 9500000,
                'salary_max' => 14000000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Financial Analyst',
                'description' => '<p><strong>Job Description:</strong></p><p>Analyze financial data and create forecasts to support business decision-making. You will prepare detailed financial reports and recommendations for management.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Finance or Accounting</li><li>3+ years experience in financial analysis</li><li>Advanced Excel skills</li><li>Knowledge of financial modeling</li><li>Strong attention to detail</li></ul><p><strong>Benefits:</strong></p><ul><li>Professional certification support</li><li>Annual financial planning bonus</li><li>Career advancement opportunities</li></ul>',
                'department' => 'Finance',
                'company_name' => 'FinanceMax Corporation',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(5),
                'expired_date' => Carbon::now()->addDays(25),
                'location' => 'Bandung',
                'salary_min' => 7500000,
                'salary_max' => 11500000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Customer Success Manager',
                'description' => '<p><strong>Job Description:</strong></p><p>Build and maintain relationships with key customers to ensure their success with our products. You will identify opportunities for account growth and reduce churn.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Business or related field</li><li>4+ years experience in customer success or account management</li><li>Strong communication and presentation skills</li><li>Experience with CRM systems</li><li>Problem-solving mindset</li></ul><p><strong>Benefits:</strong></p><ul><li>Customer success training programs</li><li>Travel opportunities</li><li>Performance-based bonuses</li></ul>',
                'department' => 'Customer Success',
                'company_name' => 'ClientCare Solutions',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(2),
                'expired_date' => Carbon::now()->addDays(28),
                'location' => 'Jakarta',
                'salary_min' => 8000000,
                'salary_max' => 12500000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Quality Assurance Engineer',
                'description' => '<p><strong>Job Description:</strong></p><p>Ensure software quality through comprehensive testing strategies. You will design test cases, execute manual and automated tests, and work closely with development teams.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Computer Science or related field</li><li>3+ years experience in software testing</li><li>Knowledge of testing frameworks and tools</li><li>Experience with automation testing</li><li>Strong analytical skills</li></ul><p><strong>Benefits:</strong></p><ul><li>Testing certification programs</li><li>Quality assurance tools and licenses</li><li>Continuous learning opportunities</li></ul>',
                'department' => 'IT',
                'company_name' => 'QualityFirst Systems',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(3),
                'expired_date' => Carbon::now()->addDays(27),
                'location' => 'Surabaya',
                'salary_min' => 6000000,
                'salary_max' => 9500000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Content Writer',
                'description' => '<p><strong>Job Description:</strong></p><p>Create engaging content for various digital platforms including websites, blogs, and social media. You will develop content strategies that align with brand voice and marketing objectives.</p><p><strong>Requirements:</strong></p><ul><li>Bachelor\'s degree in Communications, Journalism, or English</li><li>2+ years experience in content writing</li><li>Strong writing and editing skills</li><li>Knowledge of SEO best practices</li><li>Experience with content management systems</li></ul><p><strong>Benefits:</strong></p><ul><li>Creative writing workshops</li><li>Content creation tools and resources</li><li>Byline opportunities</li></ul>',
                'department' => 'Marketing',
                'company_name' => 'ContentCreators Hub',
                'company_logo' => null,
                'published_date' => Carbon::now()->subDays(1),
                'expired_date' => Carbon::now()->addDays(29),
                'location' => 'Remote',
                'salary_min' => 4500000,
                'salary_max' => 7000000,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($jobs as $jobData) {
            Job::create($jobData);
        }

        $this->command->info('✅ Jobs created successfully!');
        $this->command->info('📊 Total jobs created: ' . count($jobs));
    }
}
