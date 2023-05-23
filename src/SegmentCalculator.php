<?php

declare(strict_types=1);

namespace Talkroute\MessageSegmentCalculator;

class SegmentCalculator
{
    /**
     * @var array<int, array<int, int>>
     */
    private static array $unicodeToGSMCodePointMap = [
        0x000A => [0x0A],
        0x000C => [0x1B, 0x0A],
        0x000D => [0x0D],
        0x0020 => [0x20],
        0x0021 => [0x21],
        0x0022 => [0x22],
        0x0023 => [0x23],
        0x0024 => [0x02],
        0x0025 => [0x25],
        0x0026 => [0x26],
        0x0027 => [0x27],
        0x0028 => [0x28],
        0x0029 => [0x29],
        0x002A => [0x2A],
        0x002B => [0x2B],
        0x002C => [0x2C],
        0x002D => [0x2D],
        0x002E => [0x2E],
        0x002F => [0x2F],
        0x0030 => [0x30],
        0x0031 => [0x31],
        0x0032 => [0x32],
        0x0033 => [0x33],
        0x0034 => [0x34],
        0x0035 => [0x35],
        0x0036 => [0x36],
        0x0037 => [0x37],
        0x0038 => [0x38],
        0x0039 => [0x39],
        0x003A => [0x3A],
        0x003B => [0x3B],
        0x003C => [0x3C],
        0x003D => [0x3D],
        0x003E => [0x3E],
        0x003F => [0x3F],
        0x0040 => [0x00],
        0x0041 => [0x41],
        0x0042 => [0x42],
        0x0043 => [0x43],
        0x0044 => [0x44],
        0x0045 => [0x45],
        0x0046 => [0x46],
        0x0047 => [0x47],
        0x0048 => [0x48],
        0x0049 => [0x49],
        0x004A => [0x4A],
        0x004B => [0x4B],
        0x004C => [0x4C],
        0x004D => [0x4D],
        0x004E => [0x4E],
        0x004F => [0x4F],
        0x0050 => [0x50],
        0x0051 => [0x51],
        0x0052 => [0x52],
        0x0053 => [0x53],
        0x0054 => [0x54],
        0x0055 => [0x55],
        0x0056 => [0x56],
        0x0057 => [0x57],
        0x0058 => [0x58],
        0x0059 => [0x59],
        0x005A => [0x5A],
        0x005B => [0x1B, 0x3C],
        0x005C => [0x1B, 0x2F],
        0x005D => [0x1B, 0x3E],
        0x005E => [0x1B, 0x14],
        0x005F => [0x11],
        0x0061 => [0x61],
        0x0062 => [0x62],
        0x0063 => [0x63],
        0x0064 => [0x64],
        0x0065 => [0x65],
        0x0066 => [0x66],
        0x0067 => [0x67],
        0x0068 => [0x68],
        0x0069 => [0x69],
        0x006A => [0x6A],
        0x006B => [0x6B],
        0x006C => [0x6C],
        0x006D => [0x6D],
        0x006E => [0x6E],
        0x006F => [0x6F],
        0x0070 => [0x70],
        0x0071 => [0x71],
        0x0072 => [0x72],
        0x0073 => [0x73],
        0x0074 => [0x74],
        0x0075 => [0x75],
        0x0076 => [0x76],
        0x0077 => [0x77],
        0x0078 => [0x78],
        0x0079 => [0x79],
        0x007A => [0x7A],
        0x007B => [0x1B, 0x28],
        0x007C => [0x1B, 0x40],
        0x007D => [0x1B, 0x29],
        0x007E => [0x1B, 0x3D],
        0x00A1 => [0x40],
        0x00A3 => [0x01],
        0x00A4 => [0x24],
        0x00A5 => [0x03],
        0x00A7 => [0x5F],
        0x00BF => [0x60],
        0x00C4 => [0x5B],
        0x00C5 => [0x0E],
        0x00C6 => [0x1C],
        0x00C9 => [0x1F],
        0x00D1 => [0x5D],
        0x00D6 => [0x5C],
        0x00D8 => [0x0B],
        0x00DC => [0x5E],
        0x00DF => [0x1E],
        0x00E0 => [0x7F],
        0x00E4 => [0x7B],
        0x00E5 => [0x0F],
        0x00E6 => [0x1D],
        0x00C7 => [0x09],
        0x00E8 => [0x04],
        0x00E9 => [0x05],
        0x00EC => [0x07],
        0x00F1 => [0x7D],
        0x00F2 => [0x08],
        0x00F6 => [0x7C],
        0x00F8 => [0x0C],
        0x00F9 => [0x06],
        0x00FC => [0x7E],
        0x0393 => [0x13],
        0x0394 => [0x10],
        0x0398 => [0x19],
        0x039B => [0x14],
        0x039E => [0x1A],
        0x03A0 => [0x16],
        0x03A3 => [0x18],
        0x03A6 => [0x12],
        0x03A8 => [0x17],
        0x03A9 => [0x15],
        0x20AC => [0x1B, 0x65],
    ];

    private static array $smartEncodingMap = [
        "\u{00ab}" => '"', // LEFT-POINTING DOUBLE ANGLE QUOTATION MARK
        "\u{00bb}" => '"', // RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK
        "\u{201c}" => '"', // LEFT DOUBLE QUOTATION MARK
        "\u{201d}" => '"', // RIGHT DOUBLE QUOTATION MARK
        "\u{02ba}" => '"', // MODIFIER LETTER DOUBLE PRIME
        "\u{02ee}" => '"', // MODIFIER LETTER DOUBLE APOSTROPHE
        "\u{201f}" => '"', // DOUBLE HIGH-REVERSED-9 QUOTATION MARK
        "\u{275d}" => '"', // HEAVY DOUBLE TURNED COMMA QUOTATION MARK ORNAMENT
        "\u{275e}" => '"', // HEAVY DOUBLE COMMA QUOTATION MARK ORNAMENT
        "\u{301d}" => '"', // REVERSED DOUBLE PRIME QUOTATION MARK
        "\u{301e}" => '"', // DOUBLE PRIME QUOTATION MARK
        "\u{ff02}" => '"', // FULLWIDTH QUOTATION MARK
        "\u{2018}" => "'", // LEFT SINGLE QUOTATION MARK
        "\u{2019}" => "'", // RIGHT SINGLE QUOTATION MARK
        "\u{02BB}" => "'", // MODIFIER LETTER TURNED COMMA
        "\u{02c8}" => "'", // MODIFIER LETTER VERTICAL LINE
        "\u{02bc}" => "'", // MODIFIER LETTER APOSTROPHE
        "\u{02bd}" => "'", // MODIFIER LETTER REVERSED COMMA
        "\u{02b9}" => "'", // MODIFIER LETTER PRIME
        "\u{201b}" => "'", // SINGLE HIGH-REVERSED-9 QUOTATION MARK
        "\u{ff07}" => "'", // FULLWIDTH APOSTROPHE
        "\u{00b4}" => "'", // ACUTE ACCENT
        "\u{02ca}" => "'", // MODIFIER LETTER ACUTE ACCENT
        "\u{0060}" => "'", // GRAVE ACCENT
        "\u{02cb}" => "'", // MODIFIER LETTER GRAVE ACCENT
        "\u{275b}" => "'", // HEAVY SINGLE TURNED COMMA QUOTATION MARK ORNAMENT
        "\u{275c}" => "'", // HEAVY SINGLE COMMA QUOTATION MARK ORNAMENT
        "\u{0313}" => "'", // COMBINING COMMA ABOVE
        "\u{0314}" => "'", // COMBINING REVERSED COMMA ABOVE
        "\u{fe10}" => "'", // PRESENTATION FORM FOR VERTICAL COMMA
        "\u{fe11}" => "'", // PRESENTATION FORM FOR VERTICAL IDEOGRAPHIC COMMA
        "\u{00F7}" => '/', // DIVISION SIGN
        "\u{00bc}" => '1/4', // VULGAR FRACTION ONE QUARTER
        "\u{00bd}" => '1/2', // VULGAR FRACTION ONE HALF
        "\u{00be}" => '3/4', // VULGAR FRACTION THREE QUARTERS
        "\u{29f8}" => '/', // BIG SOLIDUS
        "\u{0337}" => '/', // COMBINING SHORT SOLIDUS OVERLAY
        "\u{0338}" => '/', // COMBINING LONG SOLIDUS OVERLAY
        "\u{2044}" => '/', // FRACTION SLASH
        "\u{2215}" => '/', // DIVISION SLASH
        "\u{ff0f}" => '/', // FULLWIDTH SOLIDUS
        "\u{29f9}" => '\\', // BIG REVERSE SOLIDUS
        "\u{29f5}" => '\\', // REVERSE SOLIDUS OPERATOR
        "\u{20e5}" => '\\', // COMBINING REVERSE SOLIDUS OVERLAY
        "\u{fe68}" => '\\', // SMALL REVERSE SOLIDUS
        "\u{ff3c}" => '\\', // FULLWIDTH REVERSE SOLIDUS
        "\u{0332}" => '_', // COMBINING LOW LINE
        "\u{ff3f}" => '_', // FULLWIDTH LOW LINE
        "\u{20d2}" => '|', // COMBINING LONG VERTICAL LINE OVERLAY
        "\u{20d3}" => '|', // COMBINING SHORT VERTICAL LINE OVERLAY
        "\u{2223}" => '|', // DIVIDES
        "\u{ff5c}" => '|', // FULLWIDTH VERTICAL LINE
        "\u{23b8}" => '|', // LEFT VERTICAL BOX LINE
        "\u{23b9}" => '|', // RIGHT VERTICAL BOX LINE
        "\u{23d0}" => '|', // VERTICAL LINE EXTENSION
        "\u{239c}" => '|', // LEFT PARENTHESIS EXTENSION
        "\u{239f}" => '|', // RIGHT PARENTHESIS EXTENSION
        "\u{23bc}" => '-', // HORIZONTAL SCAN LINE-7
        "\u{23bd}" => '-', // HORIZONTAL SCAN LINE-9
        "\u{2015}" => '-', // HORIZONTAL BAR
        "\u{fe63}" => '-', // SMALL HYPHEN-MINUS
        "\u{ff0d}" => '-', // FULLWIDTH HYPHEN-MINUS
        "\u{2010}" => '-', // HYPHEN
        "\u{2043}" => '-', // HYPHEN BULLET
        "\u{fe6b}" => '@', // SMALL COMMERCIAL AT
        "\u{ff20}" => '@', // FULLWIDTH COMMERCIAL AT
        "\u{fe69}" => '$', // SMALL DOLLAR SIGN
        "\u{ff04}" => '$', // FULLWIDTH DOLLAR SIGN
        "\u{01c3}" => '!', // LATIN LETTER RETROFLEX CLICK
        "\u{fe15}" => '!', // PRESENTATION FORM FOR VERTICAL EXLAMATION MARK
        "\u{fe57}" => '!', // SMALL EXCLAMATION MARK
        "\u{ff01}" => '!', // FULLWIDTH EXCLAMATION MARK
        "\u{fe5f}" => '#', // SMALL NUMBER SIGN
        "\u{ff03}" => '#', // FULLWIDTH NUMBER SIGN
        "\u{fe6a}" => '%', // SMALL PERCENT SIGN
        "\u{ff05}" => '%', // FULLWIDTH PERCENT SIGN
        "\u{fe60}" => '&', // SMALL AMPERSAND
        "\u{ff06}" => '&', // FULLWIDTH AMPERSAND
        "\u{201a}" => ',', // SINGLE LOW-9 QUOTATION MARK
        "\u{0326}" => ',', // COMBINING COMMA BELOW
        "\u{fe50}" => ',', // SMALL COMMA
        "\u{fe51}" => ',', // SMALL IDEOGRAPHIC COMMA
        "\u{ff0c}" => ',', // FULLWIDTH COMMA
        "\u{ff64}" => ',', // HALFWIDTH IDEOGRAPHIC COMMA
        "\u{2768}" => '(', // MEDIUM LEFT PARENTHESIS ORNAMENT
        "\u{276a}" => '(', // MEDIUM FLATTENED LEFT PARENTHESIS ORNAMENT
        "\u{fe59}" => '(', // SMALL LEFT PARENTHESIS
        "\u{ff08}" => '(', // FULLWIDTH LEFT PARENTHESIS
        "\u{27ee}" => '(', // MATHEMATICAL LEFT FLATTENED PARENTHESIS
        "\u{2985}" => '(', // LEFT WHITE PARENTHESIS
        "\u{2769}" => ')', // MEDIUM RIGHT PARENTHESIS ORNAMENT
        "\u{276b}" => ')', // MEDIUM FLATTENED RIGHT PARENTHESIS ORNAMENT
        "\u{fe5a}" => ')', // SMALL RIGHT PARENTHESIS
        "\u{ff09}" => ')', // FULLWIDTH RIGHT PARENTHESIS
        "\u{27ef}" => ')', // MATHEMATICAL RIGHT FLATTENED PARENTHESIS
        "\u{2986}" => ')', // RIGHT WHITE PARENTHESIS
        "\u{204e}" => '*', // LOW ASTERISK
        "\u{2217}" => '*', // ASTERISK OPERATOR
        "\u{229B}" => '*', // CIRCLED ASTERISK OPERATOR
        "\u{2722}" => '*', // FOUR TEARDROP-SPOKED ASTERISK
        "\u{2723}" => '*', // FOUR BALLOON-SPOKED ASTERISK
        "\u{2724}" => '*', // HEAVY FOUR BALLOON-SPOKED ASTERISK
        "\u{2725}" => '*', // FOUR CLUB-SPOKED ASTERISK
        "\u{2731}" => '*', // HEAVY ASTERISK
        "\u{2732}" => '*', // OPEN CENTRE ASTERISK
        "\u{2733}" => '*', // EIGHT SPOKED ASTERISK
        "\u{273a}" => '*', // SIXTEEN POINTED ASTERISK
        "\u{273b}" => '*', // TEARDROP-SPOKED ASTERISK
        "\u{273c}" => '*', // OPEN CENTRE TEARDROP-SPOKED ASTERISK
        "\u{273d}" => '*', // HEAVY TEARDROP-SPOKED ASTERISK
        "\u{2743}" => '*', // HEAVY TEARDROP-SPOKED PINWHEEL ASTERISK
        "\u{2749}" => '*', // BALLOON-SPOKED ASTERISK
        "\u{274a}" => '*', // EIGHT TEARDROP-SPOKED PROPELLER ASTERISK
        "\u{274b}" => '*', // HEAVY EIGHT TEARDROP-SPOKED PROPELLER ASTERISK
        "\u{29c6}" => '*', // SQUARED ASTERISK
        "\u{fe61}" => '*', // SMALL ASTERISK
        "\u{ff0a}" => '*', // FULLWIDTH ASTERISK
        "\u{02d6}" => '+', // MODIFIER LETTER PLUS SIGN
        "\u{fe62}" => '+', // SMALL PLUS SIGN
        "\u{ff0b}" => '+', // FULLWIDTH PLUS SIGN
        "\u{3002}" => '.', // IDEOGRAPHIC FULL STOP
        "\u{fe52}" => '.', // SMALL FULL STOP
        "\u{ff0e}" => '.', // FULLWIDTH FULL STOP
        "\u{ff61}" => '.', // HALFWIDTH IDEOGRAPHIC FULL STOP
        "\u{ff10}" => '0', // FULLWIDTH DIGIT ZERO
        "\u{ff11}" => '1', // FULLWIDTH DIGIT ONE
        "\u{ff12}" => '2', // FULLWIDTH DIGIT TWO
        "\u{ff13}" => '3', // FULLWIDTH DIGIT THREE
        "\u{ff14}" => '4', // FULLWIDTH DIGIT FOUR
        "\u{ff15}" => '5', // FULLWIDTH DIGIT FIVE
        "\u{ff16}" => '6', // FULLWIDTH DIGIT SIX
        "\u{ff17}" => '7', // FULLWIDTH DIGIT SEVEN
        "\u{ff18}" => '8', // FULLWIDTH DIGIT EIGHT
        "\u{ff19}" => '9', // FULLWIDTH DIGIT NINE
        "\u{02d0}" => ' =>', // MODIFIER LETTER TRIANGULAR COLON
        "\u{02f8}" => ' =>', // MODIFIER LETTER RAISED COLON
        "\u{2982}" => ' =>', // Z NOTATION TYPE COLON
        "\u{a789}" => ' =>', // MODIFIER LETTER COLON
        "\u{fe13}" => ' =>', // PRESENTATION FORM FOR VERTICAL COLON
        "\u{ff1a}" => ' =>', // FULLWIDTH COLON
        "\u{204f}" => ';', // REVERSED SEMICOLON
        "\u{fe14}" => ';', // PRESENTATION FORM FOR VERTICAL SEMICOLON
        "\u{fe54}" => ';', // SMALL SEMICOLON
        "\u{ff1b}" => ';', // FULLWIDTH SEMICOLON
        "\u{fe64}" => '<', // SMALL LESS-THAN SIGN
        "\u{ff1c}" => '<', // FULLWIDTH LESS-THAN SIGN
        "\u{0347}" => '=', // COMBINING EQUALS SIGN BELOW
        "\u{a78a}" => '=', // MODIFIER LETTER SHORT EQUALS SIGN
        "\u{fe66}" => '=', // SMALL EQUALS SIGN
        "\u{ff1d}" => '=', // FULLWIDTH EQUALS SIGN
        "\u{fe65}" => '>', // SMALL GREATER-THAN SIGN
        "\u{ff1e}" => '>', // FULLWIDTH GREATER-THAN SIGN
        "\u{fe16}" => '?', // PRESENTATION FORM FOR VERTICAL QUESTION MARK
        "\u{fe56}" => '?', // SMALL QUESTION MARK
        "\u{ff1f}" => '?', // FULLWIDTH QUESTION MARK
        "\u{ff21}" => 'A', // FULLWIDTH LATIN CAPITAL LETTER A
        "\u{1d00}" => 'A', // LATIN LETTER SMALL CAPITAL A
        "\u{ff22}" => 'B', // FULLWIDTH LATIN CAPITAL LETTER B
        "\u{0299}" => 'B', // LATIN LETTER SMALL CAPITAL B
        "\u{ff23}" => 'C', // FULLWIDTH LATIN CAPITAL LETTER C
        "\u{1d04}" => 'C', // LATIN LETTER SMALL CAPITAL C
        "\u{ff24}" => 'D', // FULLWIDTH LATIN CAPITAL LETTER D
        "\u{1d05}" => 'D', // LATIN LETTER SMALL CAPITAL D
        "\u{ff25}" => 'E', // FULLWIDTH LATIN CAPITAL LETTER E
        "\u{1d07}" => 'E', // LATIN LETTER SMALL CAPITAL E
        "\u{ff26}" => 'F', // FULLWIDTH LATIN CAPITAL LETTER F
        "\u{a730}" => 'F', // LATIN LETTER SMALL CAPITAL F
        "\u{ff27}" => 'G', // FULLWIDTH LATIN CAPITAL LETTER G
        "\u{0262}" => 'G', // LATIN LETTER SMALL CAPITAL G
        "\u{ff28}" => 'H', // FULLWIDTH LATIN CAPITAL LETTER H
        "\u{029c}" => 'H', // LATIN LETTER SMALL CAPITAL H
        "\u{ff29}" => 'I', // FULLWIDTH LATIN CAPITAL LETTER I
        "\u{026a}" => 'I', // LATIN LETTER SMALL CAPITAL I
        "\u{ff2a}" => 'J', // FULLWIDTH LATIN CAPITAL LETTER J
        "\u{1d0a}" => 'J', // LATIN LETTER SMALL CAPITAL J
        "\u{ff2b}" => 'K', // FULLWIDTH LATIN CAPITAL LETTER K
        "\u{1d0b}" => 'K', // LATIN LETTER SMALL CAPITAL K
        "\u{ff2c}" => 'L', // FULLWIDTH LATIN CAPITAL LETTER L
        "\u{029f}" => 'L', // LATIN LETTER SMALL CAPITAL L
        "\u{ff2d}" => 'M', // FULLWIDTH LATIN CAPITAL LETTER M
        "\u{1d0d}" => 'M', // LATIN LETTER SMALL CAPITAL M
        "\u{ff2e}" => 'N', // FULLWIDTH LATIN CAPITAL LETTER N
        "\u{0274}" => 'N', // LATIN LETTER SMALL CAPITAL N
        "\u{ff2f}" => 'O', // FULLWIDTH LATIN CAPITAL LETTER O
        "\u{1d0f}" => 'O', // LATIN LETTER SMALL CAPITAL O
        "\u{ff30}" => 'P', // FULLWIDTH LATIN CAPITAL LETTER P
        "\u{1d18}" => 'P', // LATIN LETTER SMALL CAPITAL P
        "\u{ff31}" => 'Q', // FULLWIDTH LATIN CAPITAL LETTER Q
        "\u{ff32}" => 'R', // FULLWIDTH LATIN CAPITAL LETTER R
        "\u{0280}" => 'R', // LATIN LETTER SMALL CAPITAL R
        "\u{ff33}" => 'S', // FULLWIDTH LATIN CAPITAL LETTER S
        "\u{a731}" => 'S', // LATIN LETTER SMALL CAPITAL S
        "\u{ff34}" => 'T', // FULLWIDTH LATIN CAPITAL LETTER T
        "\u{1d1b}" => 'T', // LATIN LETTER SMALL CAPITAL T
        "\u{ff35}" => 'U', // FULLWIDTH LATIN CAPITAL LETTER U
        "\u{1d1c}" => 'U', // LATIN LETTER SMALL CAPITAL U
        "\u{ff36}" => 'V', // FULLWIDTH LATIN CAPITAL LETTER V
        "\u{1d20}" => 'V', // LATIN LETTER SMALL CAPITAL V
        "\u{ff37}" => 'W', // FULLWIDTH LATIN CAPITAL LETTER W
        "\u{1d21}" => 'W', // LATIN LETTER SMALL CAPITAL W
        "\u{ff38}" => 'X', // FULLWIDTH LATIN CAPITAL LETTER X
        "\u{ff39}" => 'Y', // FULLWIDTH LATIN CAPITAL LETTER Y
        "\u{028f}" => 'Y', // LATIN LETTER SMALL CAPITAL Y
        "\u{ff3a}" => 'Z', // FULLWIDTH LATIN CAPITAL LETTER Z
        "\u{1d22}" => 'Z', // LATIN LETTER SMALL CAPITAL Z
        "\u{02c6}" => '^', // MODIFIER LETTER CIRCUMFLEX ACCENT
        "\u{0302}" => '^', // COMBINING CIRCUMFLEX ACCENT
        "\u{ff3e}" => '^', // FULLWIDTH CIRCUMFLEX ACCENT
        "\u{1dcd}" => '^', // COMBINING DOUBLE CIRCUMFLEX ABOVE
        "\u{2774}" => '{', // MEDIUM LEFT CURLY BRACKET ORNAMENT
        "\u{fe5b}" => '{', // SMALL LEFT CURLY BRACKET
        "\u{ff5b}" => '{', // FULLWIDTH LEFT CURLY BRACKET
        "\u{2775}' => '}", // MEDIUM RIGHT CURLY BRACKET ORNAMENT
        "\u{fe5c}' => '}", // SMALL RIGHT CURLY BRACKET
        "\u{ff5d}' => '}", // FULLWIDTH RIGHT CURLY BRACKET
        "\u{ff3b}" => '[', // FULLWIDTH LEFT SQUARE BRACKET
        "\u{ff3d}" => ']', // FULLWIDTH RIGHT SQUARE BRACKET
        "\u{02dc}" => '~', // SMALL TILDE
        "\u{02f7}" => '~', // MODIFIER LETTER LOW TILDE
        "\u{0303}" => '~', // COMBINING TILDE
        "\u{0330}" => '~', // COMBINING TILDE BELOW
        "\u{0334}" => '~', // COMBINING TILDE OVERLAY
        "\u{223c}" => '~', // TILDE OPERATOR
        "\u{ff5e}" => '~', // FULLWIDTH TILDE
        "\u{00a0}" => '  ', // NO-BREAK SPACE
        "\u{2000}" => '  ', // EN QUAD
        "\u{2002}" => '  ', // EN SPACE
        "\u{2003}" => '  ', // EM SPACE
        "\u{2004}" => '  ', // THREE-PER-EM SPACE
        "\u{2005}" => '  ', // FOUR-PER-EM SPACE
        "\u{2006}" => '  ', // SIX-PER-EM SPACE
        "\u{2007}" => '  ', // FIGURE SPACE
        "\u{2008}" => '  ', // PUNCTUATION SPACE
        "\u{2009}" => '  ', // THIN SPACE
        "\u{200a}" => '  ', // HAIR SPACE
        "\u{202f}" => '  ', // NARROW NO-BREAK SPACE
        "\u{205f}" => '  ', // MEDIUM MATHEMATICAL SPACE
        "\u{3000}" => '  ', // IDEOGRAHPIC SPACE
        "\u{008d}" => '  ', // REVERSE LINE FEED (standard LF looks like \n, this looks like a space)
        "\u{009f}" => '  ', // <control>
        "\u{0080}" => '  ', // C1 CONTROL CODES
        "\u{0090}" => '  ', // DEVICE CONTROL STRING
        "\u{009b}" => '  ', // CONTROL SEQUENCE INTRODUCER
        "\u{0010}" => '', // ESCAPE, DATA LINK (not visible)
        "\u{0009}" => '       ', // TAB (7 spaces based on print statement in Python interpreter)
        "\u{0000}" => '', // NULL
        "\u{0003}" => '', // END OF TEXT
        "\u{0004}" => '', // END OF TRANSMISSION
        "\u{0017}" => '', // END OF TRANSMISSION BLOCK
        "\u{0019}" => '', // END OF MEDIUM
        "\u{0011}" => '', // DEVICE CONTROL ONE
        "\u{0012}" => '', // DEVICE CONTROL TWO
        "\u{0013}" => '', // DEVICE CONTROL THREE
        "\u{0014}" => '', // DEVICE CONTROL FOUR
        "\u{2060}" => '', // WORD JOINER
        "\u{2017}" => "'", // Horizontal ellipsis
        "\u{2014}" => '-', // Single low-9 quotation mark
        "\u{2013}" => '-', // Single high-reversed-9 quotation mark
        "\u{2039}" => '>', // Single left-pointing angle quotation mark
        "\u{203A}" => '<', // Single right-pointing angle quotation mark
        "\u{203C}" => '!!', // Double exclamation mark
        "\u{201E}" => '"', // Double low line
        "\u{2028}" => ' ', // Whitespace => Line Separator
        "\u{2029}" => ' ', // Whitespace => Paragraph Separator
        "\u{2026}" => '...', // Whitespace => Narrow No-Break Space
        "\u{2001}" => ' ', // Whitespace => Medium Mathematical Space
        "\u{200b}" => '', // ZERO WIDTH SPACE
        "\u{3001}" => ',', // IDEOGRAPHIC COMMA
        "\u{FEFF}" => '', // ZERO WIDTH NO-BREAK SPACE
        "\u{2022}" => '-', // Bullet
    ];

    private static function isTranscodableToGSMEncoding(string $codePoint): bool
    {
        return array_key_exists($codePoint, self::$unicodeToGSMCodePointMap);
    }

    /**
     * Internal encoding must be set to UTF-8,
     * and the input string must be UTF-8 encoded for this to work correctly.
     */
    private static function ucs2StringCount(string $string): float|int
    {
        $utf16str = mb_convert_encoding($string, 'UTF-16', 'UTF-8');
        // C* option gives an unsigned 16-bit integer representation of each byte
        // which option you choose doesn't actually matter as long as you get one value per byte
        $byteArray = unpack('C*', $utf16str);

        return count($byteArray) / 2;
    }

    private static function getGraphemes(string $message): array
    {
        $graphemes = [];
        for ($i = 0; $i < grapheme_strlen($message); ++$i) {
            $graphemes[] = grapheme_substr($message, $i, 1);
        }

        return $graphemes;
    }

    private static function doesGraphemesContainUCS2(array $graphemes): bool
    {
        $contains_uc2 = false;
        foreach ($graphemes as $grapheme) {
            $codePoint = \IntlChar::ord($grapheme);
            if (!self::isTranscodableToGSMEncoding((string) $codePoint)) {
                $contains_uc2 = true;

                break;
            }
        }

        return $contains_uc2;
    }

    /**
     * Replaces Unicode characters with ASCII symbols.
     */
    private static function replaceUnicodeWithAscii(string $text): string
    {
        return str_replace(array_keys(self::$smartEncodingMap), array_values(self::$smartEncodingMap), $text);
    }

    /**
     * segmentsCount - segments a message into GSM 7-bit or UCS-2 segments and returns the number of segments.
     *
     * @return int number of segments
     */
    public static function segmentsCount(string $message, bool $smartEncoding = false): int
    {
        return (int) ceil(self::bitsCount($message, $smartEncoding) / 1120);
    }

    /**
     * bitsCount - segments a message into GSM 7-bit or UCS-2 segments and returns the number of bits.
     *
     * @return int number of bits
     */
    public static function bitsCount(string $message, bool $smartEncoding = false): int
    {
        if ($smartEncoding) {
            $message = self::replaceUnicodeWithAscii($message);
        }
        $graphemes = self::getGraphemes($message);

        if (!self::doesGraphemesContainUCS2($graphemes)) {
            return count($graphemes) * 7;
        }

        return (int) self::ucs2StringCount($message) * 16;
    }
}
