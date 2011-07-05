#!/usr/bin/env ruby
# encoding: utf-8

# ruby version of Unicode table converter

require 'rubygems'
begin
  require 'bundler/setup'
rescue LoadError
  puts "You should install Bundler using 'gem install bundler' and run 'bundle install'"
end

require 'yajl'
require 'fastercsv'

VALID_ESCAPES = %w[ amp lt gt quot ].freeze
UNICODE_DB    = File.expand_path('./db.csv', File.dirname(__FILE__)).freeze

csv = FasterCSV.open(
  UNICODE_DB,
  'rb',
  :col_sep => "\t",
  :row_sep => "\n",
  :quote_char => '`',
  :headers => :first_row
)

db = { '__replacement' => 65533 }

csv.each do |row|
  row['Unicode code point (decimal)'] =~ /(\S+)\s+\((\S+)\)/
  uch, dec, name = $1, $2.to_i, row['Name']
  next if VALID_ESCAPES.include?(name)
  db[name] = dec
end

json = Yajl::Encoder.encode(db)
json.gsub!('"', '')

puts json

# Paste in jwysiwyg and you're done.
