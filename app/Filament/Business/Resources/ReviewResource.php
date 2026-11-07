<?php

namespace App\Filament\Business\Resources;

use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\ReviewResource\Pages;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?string $modelLabel = 'Review';

    protected static ?string $pluralModelLabel = 'Reviews';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'reviews');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('rating')->numeric(),
                Forms\Components\Textarea::make('body')->columnSpanFull(),
                Forms\Components\Toggle::make('is_published'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rating')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('body')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
