<?php

namespace App\Services;

use App\Models\CvUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvParsingService
{
    /**
     * Parse a CV file and extract useful information.
     *
     * @param CvUpload $cv
     * @return array
     */
    public function parseCv(CvUpload $cv): array
    {
        $cvPath = storage_path('app/public/' . $cv->file_path);
        
        $parsedData = [];
        
        if (strtolower($cv->file_type) === 'pdf') {
            $parsedData = $this->parsePdf($cvPath);
        } elseif (in_array(strtolower($cv->file_type), ['doc', 'docx'])) {
            $parsedData = $this->parseWordDocument($cvPath);
        } else {
            return ['error' => 'Unsupported file type: ' . $cv->file_type];
        }
        
        // Extract key information
        $extracted = [
            'raw_text' => $parsedData['text'] ?? '',
            'skills' => $this->extractSkills($parsedData['text'] ?? ''),
            'work_experience' => $this->extractWorkExperience($parsedData['text'] ?? ''),
            'education' => $this->extractEducation($parsedData['text'] ?? ''),
            'contact_info' => $this->extractContactInfo($parsedData['text'] ?? ''),
            'languages' => $this->extractLanguages($parsedData['text'] ?? ''),
            'summary' => $this->generateSummary($parsedData['text'] ?? ''),
            'location' => $this->extractLocation($parsedData['text'] ?? ''),
        ];

        return $extracted;
    }
    
    /**
     * Parse a PDF file to extract text content.
     */
    private function parsePdf(string $filePath): array
    {
        try {
            if (!class_exists('\Smalot\PdfParser\Parser')) {
                // If PDF parser is not installed, just return empty content
                return ['text' => 'PDF parsing library not installed'];
            }
            
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($filePath);
            return ['text' => $pdf->getText()];
        } catch (\Exception $e) {
            return ['text' => '', 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Parse Word document to extract text content.
     */
    private function parseWordDocument(string $filePath): array
    {
        try {
            // Try to extract text from Word documents
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            if ($extension === 'docx') {
                if (!class_exists('\PhpOffice\PhpWord\IOFactory')) {
                    return ['text' => 'Word processing library not installed'];
                }
                
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                $text = '';
                
                foreach ($phpWord->getSections() as $section) {
                    $elements = $section->getElements();
                    foreach ($elements as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                
                return ['text' => $text];
            } else {
                return ['text' => 'Processing Word documents may require additional libraries'];
            }
        } catch (\Exception $e) {
            return ['text' => '', 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Extract skills from CV text.
     */
    private function extractSkills(string $text): array
    {
        $text = strtolower($text);
        $commonSkills = [
            'javascript', 'python', 'java', 'react', 'angular', 'vue', 'laravel', 'php', 'html', 'css',
            'sql', 'mysql', 'postgresql', 'mongodb', 'aws', 'azure', 'gcp', 'docker', 'kubernetes',
            'project management', 'leadership', 'communication', 'teamwork', 'problem solving',
            'sales', 'marketing', 'design', 'research', 'data analysis', 'machine learning',
            'digital marketing', 'social media', 'seo', 'content writing', 'graphic design',
            'customer service', 'negotiation', 'time management', 'organizational'
        ];
        
        $skills = [];
        foreach ($commonSkills as $skill) {
            if (stripos($text, $skill) !== false) {
                $skills[] = ucfirst($skill);
            }
        }
        
        // Remove duplicates and limit to top skills
        $skills = array_unique($skills);
        return array_slice($skills, 0, 20); // Return top 20 skills
    }
    
    /**
     * Extract work experience from CV text.
     */
    private function extractWorkExperience(string $text): array
    {
        $lines = explode("\n", $text);
        $experiences = [];
        
        foreach ($lines as $line) {
            // Look for typical work experience indicators
            if (preg_match('/(manager|developer|engineer|analyst|specialist|director|consultant|lead|senior|junior|intern|assistant|coordinator|executive)/i', $line)) {
                $experiences[] = [
                    'position' => $this->extractPosition($line),
                    'company' => $this->extractCompany($line),
                    'dates' => $this->extractDates($line)
                ];
            }
        }
        
        return $experiences;
    }
    
    /**
     * Extract position from a line of text.
     */
    private function extractPosition(string $line): string
    {
        // Look for common job titles
        $jobTitles = [
            'manager', 'developer', 'engineer', 'analyst', 'specialist', 'director', 
            'consultant', 'lead', 'senior', 'junior', 'intern', 'assistant', 
            'coordinator', 'executive', 'supervisor', 'officer', 'administrator',
            'programmer', 'architect', 'designer', 'tester', 'accountant', 'auditor'
        ];
        
        foreach ($jobTitles as $title) {
            $pattern = '/\b' . preg_quote($title, '/') . '\b/i';
            if (preg_match($pattern, $line, $matches)) {
                // Extract the actual position
                $words = explode(' ', $line);
                $position = '';
                $found = false;
                
                foreach ($words as $word) {
                    if (stripos($word, $title) !== false) {
                        $position .= $word . ' ';
                        $found = true;
                    } elseif ($found) {
                        // Check if this word is related to the position
                        if (preg_match('/(sr\.?|jr\.?|i|ii|iii|iv|v)$/i', $word)) {
                            $position .= $word . ' ';
                        } else {
                            break; // End of position title
                        }
                    }
                }
                
                return trim($position);
            }
        }
        
        return '';
    }
    
    /**
     * Extract company name from a line of text.
     */
    private function extractCompany(string $line): string
    {
        // Common indicators of company names
        $companyIndicators = [
            'inc', 'ltd', 'llc', 'corporation', 'company', 'group', 'associates', 'solutions',
            'technologies', 'systems', 'enterprises', 'services', 'consulting', 'partners'
        ];
        
        $words = explode(' ', $line);
        $company = '';
        
        foreach ($words as $word) {
            $wordClean = trim(preg_replace('/[.,]/', '', $word));
            if (strlen($wordClean) > 2 && !in_array(strtolower($wordClean), ['the', 'and', 'of', 'for', 'with', 'on', 'at', 'in', 'to', 'from'])) {
                $company .= $wordClean . ' ';
            }
        }
        
        return trim(substr($company, 0, 100)); // Limit length
    }
    
    /**
     * Extract dates from a line of text.
     */
    private function extractDates(string $line): string
    {
        // Match common date patterns
        $patterns = [
            '/\b\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}\b/', // MM/DD/YYYY or MM-DD-YYYY
            '/\b\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}\b/', // YYYY/MM/DD or YYYY-MM-DD
            '/\b(?:jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)[a-z]*\s+\d{4}\b/i', // Month Year
            '/\b(?:january|february|march|april|may|june|july|august|september|october|november|december)\s+\d{4}\b/i', // Full month Year
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                return $matches[0];
            }
        }
        
        return '';
    }
    
    /**
     * Extract education from CV text.
     */
    private function extractEducation(string $text): array
    {
        $education = [];
        $lines = explode("\n", $text);
        
        foreach ($lines as $line) {
            if (preg_match('/(university|college|school|degree|bachelor|master|phd|bsc|msc|diploma|certificate|graduated)/i', $line)) {
                $education[] = [
                    'institution' => $this->extractInstitution($line),
                    'degree' => $this->extractDegree($line),
                    'field' => $this->extractFieldOfStudy($line),
                    'dates' => $this->extractDates($line)
                ];
            }
        }
        
        return $education;
    }
    
    /**
     * Extract institution name.
     */
    private function extractInstitution(string $line): string
    {
        // Look for common institution types
        $institutionTypes = [
            'university', 'college', 'institute', 'school', 'academy', 'polytechnic', 'technical'
        ];
        
        foreach ($institutionTypes as $type) {
            $pos = stripos($line, $type);
            if ($pos !== false) {
                // Extract the full name of the institution
                $before = substr($line, 0, $pos);
                $after = substr($line, $pos);
                
                // Look for words before the type
                $wordsBefore = array_reverse(explode(' ', trim($before)));
                $institution = '';
                
                foreach ($wordsBefore as $word) {
                    if (strlen($word) > 1 && !in_array(strtolower($word), ['the', 'and', 'of', 'for', 'with', 'at', 'in', 'to', 'from'])) {
                        $institution = $word . ' ' . $institution;
                    } else {
                        break;
                    }
                }
                
                return trim($institution . ' ' . $type);
            }
        }
        
        return '';
    }
    
    /**
     * Extract degree type.
     */
    private function extractDegree(string $line): string
    {
        $degrees = [
            'bachelor', 'master', 'doctorate', 'phd', 'bsc', 'msc', 'ba', 'ma', 'bs', 'ms', 'mba', 'phd', 
            'diploma', 'certificate', 'associate', 'high school', 'secondary', 'primary'
        ];
        
        foreach ($degrees as $degree) {
            if (preg_match('/\b' . preg_quote($degree, '/') . '\b/i', $line, $matches)) {
                return $matches[0];
            }
        }
        
        return '';
    }
    
    /**
     * Extract field of study.
     */
    private function extractFieldOfStudy(string $line): string
    {
        $fields = [
            'computer science', 'software engineering', 'information technology', 'mathematics',
            'physics', 'chemistry', 'biology', 'medicine', 'engineering', 'business',
            'economics', 'finance', 'marketing', 'management', 'english', 'literature',
            'history', 'psychology', 'sociology', 'law', 'education', 'nursing'
        ];
        
        foreach ($fields as $field) {
            if (stripos($line, $field) !== false) {
                return $field;
            }
        }
        
        return '';
    }
    
    /**
     * Extract contact information.
     */
    private function extractContactInfo(string $text): array
    {
        $email = '';
        $phone = '';
        $location = '';
        
        // Extract email
        preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $text, $emailMatch);
        if (!empty($emailMatch[0])) {
            $email = $emailMatch[0];
        }
        
        // Extract phone
        preg_match('/\b(\+?\d{1,3}[-.\s]?)?\(?(\d{3})\)?[-.\s]?(\d{3})[-.\s]?(\d{4})\b/', $text, $phoneMatch);
        if (!empty($phoneMatch[0])) {
            $phone = $phoneMatch[0];
        }
        
        // Extract location (simplified)
        $possibleLocations = ['london', 'new york', 'california', 'texas', 'florida', 'chicago', 
                             'paris', 'berlin', 'tokyo', 'sydney', 'toronto', 'vancouver',
                             'nairobi', 'lagos', 'cape town', 'accra', 'kampala'];
        
        foreach ($possibleLocations as $locationCheck) {
            if (stripos($text, $locationCheck) !== false) {
                $location = $locationCheck;
                break;
            }
        }
        
        return [
            'email' => $email,
            'phone' => $phone,
            'location' => $location
        ];
    }
    
    /**
     * Extract languages.
     */
    private function extractLanguages(string $text): array
    {
        $languages = [
            'english', 'spanish', 'french', 'german', 'italian', 'portuguese', 'russian',
            'chinese', 'japanese', 'korean', 'arabic', 'swahili', 'hausa', 'igbo', 'yoruba'
        ];
        
        $foundLanguages = [];
        $textLower = strtolower($text);
        
        foreach ($languages as $language) {
            if (stripos($textLower, $language) !== false) {
                $foundLanguages[] = ucfirst($language);
            }
        }
        
        return array_unique($foundLanguages);
    }
    
    /**
     * Generate a summary from the CV text.
     */
    private function generateSummary(string $text): string
    {
        // Extract the first few meaningful sentences after removing excess whitespace
        $cleanText = preg_replace('/\s+/', ' ', $text);
        $sentences = array_filter(explode('.', $cleanText));
        
        $summary = '';
        $sentenceCount = 0;
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) > 20 && !preg_match('/^\s*(email|phone|address|linkedin|github|website|url)/i', $sentence)) {
                $summary .= $sentence . '. ';
                $sentenceCount++;
                if ($sentenceCount >= 3) break; // Limit to 3 sentences
            }
        }
        
        return trim(substr($summary, 0, 500));
    }
    
    /**
     * Extract location from CV text.
     */
    private function extractLocation(string $text): string
    {
        $lines = explode("\n", $text);
        $potentialLocation = '';
        
        foreach ($lines as $line) {
            // Look for common location indicators
            if (preg_match('/(location|address|resides?|based?|city|state|country|postal|zip|p\.?\s?o\.?\s?box)/i', $line)) {
                // Extract location information
                $potentialLocation = trim(preg_replace('/^.*?(location|address|resides?|based?|city|state|country):\s*/i', '', $line));
                break;
            }
        }
        
        if (empty($potentialLocation)) {
            // If not found in structured format, try to extract from the text
            $commonCities = ['london', 'new york', 'california', 'texas', 'florida', 'chicago', 
                           'paris', 'berlin', 'tokyo', 'sydney', 'toronto', 'vancouver',
                           'nairobi', 'lagos', 'cape town', 'accra', 'kampala', 'abuja', 'durban'];
            
            foreach ($commonCities as $city) {
                if (stripos($text, $city) !== false) {
                    $potentialLocation = $city;
                    break;
                }
            }
        }
        
        return $potentialLocation;
    }
    
    /**
     * Score a CV against a job description.
     */
    public function scoreCV(CvUpload $cv, string $jobDescription): float
    {
        if (empty($cv->extracted_skills) || empty($cv->extracted_skills)) {
            // If skills haven't been parsed, parse them now
            $parsedData = $this->parseCv($cv);
            $cvSkills = $parsedData['skills'] ?? [];
        } else {
            $cvSkills = is_string($cv->extracted_skills) ? json_decode($cv->extracted_skills, true) : $cv->extracted_skills;
            if (is_null($cvSkills)) {
                $cvSkills = [];
            }
        }
        
        $jobDescription = strtolower($jobDescription);
        $matchScore = 0;
        $totalPossible = 0;
        
        foreach ($cvSkills as $skill) {
            $skillLower = strtolower(trim($skill));
            $totalPossible += 1;
            
            if (stripos($jobDescription, $skillLower) !== false) {
                $matchScore += 1;
            }
            // Check for partial matches (e.g. "javascript" vs "js")
            elseif ($this->isSimilarSkill($skillLower, $jobDescription)) {
                $matchScore += 0.5; // Partial match
            }
        }
        
        // Add bonus for education that matches
        if (!empty($cv->education)) {
            $cvEducation = is_string($cv->education) ? json_decode($cv->education, true) : $cv->education;
            if (is_null($cvEducation)) {
                $cvEducation = [];
            }
            
            foreach ($cvEducation as $education) {
                if (is_array($education) && isset($education['field'])) {
                    $fieldOfStudy = strtolower($education['field']);
                    if (stripos($jobDescription, $fieldOfStudy) !== false) {
                        $matchScore += 0.5;
                        $totalPossible += 0.5;
                    }
                }
            }
        }
        
        // Calculate normalized score (0-100)
        $score = ($totalPossible > 0) ? ($matchScore / $totalPossible) * 100 : 0;
        
        return round(min(100, $score), 2); // Cap at 100%
    }
    
    /**
     * Check if a skill is similar to any skill in the job description.
     */
    private function isSimilarSkill(string $cvSkill, string $jobDescription): bool
    {
        // Define common skill variations
        $variations = [
            ['javascript', 'js', 'ecmascript'],
            ['python', 'django', 'flask'],
            ['php', 'laravel', 'symfony'],
            ['react', 'reactjs'],
            ['angular', 'angularjs'],
            ['vue', 'vuejs'],
            ['node', 'nodejs'],
            ['sql', 'mysql', 'postgresql'],
            ['ui', 'ux', 'user experience', 'user interface']
        ];
        
        $cvSkill = strtolower($cvSkill);
        
        foreach ($variations as $variationGroup) {
            if (in_array($cvSkill, $variationGroup)) {
                foreach ($variationGroup as $variation) {
                    if (stripos($jobDescription, $variation) !== false) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}