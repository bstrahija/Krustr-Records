#!/usr/bin/env perl
use strict;
use warnings;
use Text::CSV;
use IO::File;
use JSON::XS;
use feature ':5.10';


# http://en.wikipedia.org/wiki/List_of_XML_and_HTML_character_entity_references#Character_entity_references_in_HTML
my $fh = IO::File->new('db.csv');
my $csv = Text::CSV->new({binary=>1, sep_char=>"\t", quote_char=>undef, escape_char=>undef});
$csv->column_names( map lc, @{$csv->getline($fh)} );


my $db;
while ( my $row = $csv->getline_hr( $fh ) ) {
	my ($unicode, $dec) = $row->{'unicode code point (decimal)'} =~ m/ (\S+) \s+ \( (\S+) \) /x;
	$db->{$row->{name}} = int $dec;
}

## These four elements are also valid in XML
delete $db->{$_} for qw/ amp lt gt quot /;

## &apos btw is only valid in XML, and is not an HTML entity
## So it works for XHTML documents, but not for HTML/SGML documents

## We'll create our own pseudo element for non-matches
$db->{__replacement} = int 65533;

my $json = JSON::XS->new->utf8->encode($db);
$json =~ tr/"//d;
print $json;


# Paste in jwysiwyg and you're done.
