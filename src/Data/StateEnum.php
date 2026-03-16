<?php

/**
 * StateEnum - US States, District of Columbia, and United States enumeration class.
 *
 * This enum provides a type-safe way to work with US states and territories
 * using their standard two-letter abbreviations as enum cases with integer values.
 * Each enum case corresponds to a US state or territory, with values 1-50 for states,
 * 51 for DC, and 52 for the US as a whole.
 *
 * The enum provides methods to get the full name of the state/territory
 * and its abbreviation.
 *
 * ⚠️ WARNING: These values are not documented in the LegiScan API. They are inferred from API responses. ⚠️
 *
 * Example usage:
 * ```
 * $state = StateEnum::PA;
 * $state->id();            //  38
 * $state->label();         //  "Pennsylvania"
 * $state->abbr();          //  "PA"
 * ```
 *
 * @method int id() Returns the legiscan.com ID of the state/territory
 * @method string label() Returns the full name of the state/territory
 * @method string abbr() Returns the two-letter abbreviation of the state/territory
 */

namespace WiserWebSolutions\LaravelLegiscan\Enums\Support;

enum StateEnum: int
{
    case AL = 1;  // Alabama
    case AK = 2;  // Alaska
    case AZ = 3;  // Arizona
    case AR = 4;  // Arkansas
    case CA = 5;  // California
    case CO = 6;  // Colorado
    case CT = 7;  // Connecticut
    case DE = 8;  // Delaware
    case FL = 9;  // Florida
    case GA = 10; // Georgia
    case HI = 11; // Hawaii
    case ID = 12; // Idaho
    case IL = 13; // Illinois
    case IN = 14; // Indiana
    case IA = 15; // Iowa
    case KS = 16; // Kansas
    case KY = 17; // Kentucky
    case LA = 18; // Louisiana
    case ME = 19; // Maine
    case MD = 20; // Maryland
    case MA = 21; // Massachusetts
    case MI = 22; // Michigan
    case MN = 23; // Minnesota
    case MS = 24; // Mississippi
    case MO = 25; // Missouri
    case MT = 26; // Montana
    case NE = 27; // Nebraska
    case NV = 28; // Nevada
    case NH = 29; // New Hampshire
    case NJ = 30; // New Jersey
    case NM = 31; // New Mexico
    case NY = 32; // New York
    case NC = 33; // North Carolina
    case ND = 34; // North Dakota
    case OH = 35; // Ohio
    case OK = 36; // Oklahoma
    case OR = 37; // Oregon
    case PA = 38; // Pennsylvania
    case RI = 39; // Rhode Island
    case SC = 40; // South Carolina
    case SD = 41; // South Dakota
    case TN = 42; // Tennessee
    case TX = 43; // Texas
    case UT = 44; // Utah
    case VT = 45; // Vermont
    case VA = 46; // Virginia
    case WA = 47; // Washington
    case WV = 48; // West Virginia
    case WI = 49; // Wisconsin
    case WY = 50; // Wyoming
    case DC = 51; // District of Columbia
    case US = 52; // United States

    /**
     * Get the legiscan.com ID of the state or territory.
     *
     * @return int The legiscan.com ID of the state or territory.
     */
    public function id(): int
    {
        return $this->value;
    }

    /**
     * Get the two-letter abbreviation of the state or territory.
     *
     * @return string The two-letter abbreviation of the state or territory.
     */
    public function abbr(): string
    {
        return $this->name;
    }

    /**
     * Get the full name of the state or territory.
     *
     * @return string The full name of the state or territory.
     */
    public function label(): string
    {
        return match ($this) {
            self::AL => 'Alabama',
            self::AK => 'Alaska',
            self::AZ => 'Arizona',
            self::AR => 'Arkansas',
            self::CA => 'California',
            self::CO => 'Colorado',
            self::CT => 'Connecticut',
            self::DE => 'Delaware',
            self::FL => 'Florida',
            self::GA => 'Georgia',
            self::HI => 'Hawaii',
            self::ID => 'Idaho',
            self::IL => 'Illinois',
            self::IN => 'Indiana',
            self::IA => 'Iowa',
            self::KS => 'Kansas',
            self::KY => 'Kentucky',
            self::LA => 'Louisiana',
            self::ME => 'Maine',
            self::MD => 'Maryland',
            self::MA => 'Massachusetts',
            self::MI => 'Michigan',
            self::MN => 'Minnesota',
            self::MS => 'Mississippi',
            self::MO => 'Missouri',
            self::MT => 'Montana',
            self::NE => 'Nebraska',
            self::NV => 'Nevada',
            self::NH => 'New Hampshire',
            self::NJ => 'New Jersey',
            self::NM => 'New Mexico',
            self::NY => 'New York',
            self::NC => 'North Carolina',
            self::ND => 'North Dakota',
            self::OH => 'Ohio',
            self::OK => 'Oklahoma',
            self::OR => 'Oregon',
            self::PA => 'Pennsylvania',
            self::RI => 'Rhode Island',
            self::SC => 'South Carolina',
            self::SD => 'South Dakota',
            self::TN => 'Tennessee',
            self::TX => 'Texas',
            self::UT => 'Utah',
            self::VT => 'Vermont',
            self::VA => 'Virginia',
            self::WA => 'Washington',
            self::WV => 'West Virginia',
            self::WI => 'Wisconsin',
            self::WY => 'Wyoming',
            self::DC => 'District of Columbia',
            self::US => 'United States',
        };
    }

    /**
     * Alias for label() to get the full name of the state or territory.
     *
     * @return string The full name of the state or territory.
     */
    public function name(): string
    {
        return $this->label();
    }

    /**
     * Determine if the enum case represents a US state (not DC or US).
     *
     * @return bool True if the enum case is a US state, false otherwise.
     */
    public function is_state(): bool
    {
        return $this->value <= 50;
    }
}